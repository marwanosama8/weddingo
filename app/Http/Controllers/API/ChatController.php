<?php

namespace App\Http\Controllers\API;

use App\Events\ChatRefresh;
use App\Events\PartnerSendMessage;
use App\Events\UserSendMessage;
use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\GetConversationByIdRequest;
use App\Http\Requests\GetPartnerConvByIdRequest;
use App\Http\Requests\IsConversationReadRequest;
use App\Http\Requests\PartnerSendMessageRequest;
use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\StoreMakeConversation;
use App\Http\Requests\UserSendAttachmentRequest;
use App\Models\Partner;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Musonza\Chat\Facades\ChatFacade as Chat;
use Kutia\Larafirebase\Facades\Larafirebase;

/**
 * @group Chat
 *
 * APIs for Chat Module
 */
class ChatController extends Controller
{
    use Response;
    /**
     * 
     *  Make Conversation
     *
     *  This endpoint allows you to make conversations .
     * @bodyParam partner_id string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function makeConversation(StoreMakeConversation $request)
    {
        $validator = $request->validated();

        $user = $request->user();
        $partner = Partner::find($validator['partner_id']);

        $participants = [$user, $partner];

        $conversation = Chat::createConversation($participants, ['user' => $user->name, "partner" => $partner->business_name])->makeDirect();
        return $this->messageResponse('Done');
    }

    /**
     * 
     *  User Conversation
     *
     *  This endpoint allows you to get user conversations .
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function userConversations(Request $request)
    {
        $user = $request->user();

        if ($user->partner) {
            return $this->handleError("You Are Partner, Can't use this API");
        }

        $data = $user->conversations();

        foreach ($data as $value) {
            $user = $request->user();

            if ($user->partner) {

                $conversation = Chat::conversations()->getById($value->id);

                $count =  Chat::conversation($conversation)->setParticipant($user->partner)->unreadCount();

                $getMessage = Chat::conversation($conversation)->setParticipant($user)->getMessages();
                $lastMessage =  $getMessage->last()->body ?? '';
            } else {
                $conversation = Chat::conversations()->getById($value->id);

                $count = Chat::conversation($conversation)->setParticipant($user)->unreadCount();

                $getMessage = Chat::conversation($conversation)->setParticipant($user)->getMessages();
                $lastMessage =  $getMessage->last()->body ?? '';
            }
            $value->unread_count = $count;
            $value->last_message = $lastMessage;
        }
        return $data;
    }
    /**
     * 
     *  Get Conversation
     *
     *  This endpoint allows you to get conversation by id .
     * 
     * @bodyParam conversation_id string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function getConversationById(GetConversationByIdRequest $request)
    {
        $validator = $request->validated();

        $user = $request->user();

        $conversation = Chat::conversations()->getById($validator['conversation_id']);

        $readall = Chat::conversation($conversation)->setParticipant($user)->readAll();
        $get = Chat::conversation($conversation)->setParticipant($user);
        return $this->handleResponse(true, $get->getMessages()->all());
    }
    /**
     * 
     *  User Sending Message
     *
     *  This endpoint allows you to send message by user .
     * 
     * @bodyParam conversation_id string required . 
     * @bodyParam message string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function userSendMessage(SendMessageRequest $request)
    {
        $validator = $request->validated();

        $user = $request->user();
        $conversation = Chat::conversations()->getById($validator['conversation_id']);

        $message = $validator['message'];
        $chat =   Chat::message($message)
            ->from($user)
            ->to($conversation)
            ->send();

        $time =   Carbon::parse($chat->created_at)->format('h:i');

        $this->NotificationToPartner($chat);
        $this->sendNotificationFormUser($request->user(), $message, $chat->participation_id);
        $this->RefreshChat($request->user()->id, $chat->conversation_id, $chat->body, $time);

        return $this->messageResponse('Done!');
    }
    /**
     * 
     *  Partner Sending Message
     *
     *  This endpoint allows you to send message by partner .
     * 
     * @bodyParam partner_id string required . 
     * @bodyParam conversation_id string required . 
     * @bodyParam message string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function partnerSendMessage(PartnerSendMessageRequest $request)
    {
        $validator = $request->validated();

        $partner = Partner::find($validator['partner_id']);
        $conversation = Chat::conversations()->getById($validator['conversation_id']);

        $message = $validator['message'];
        $chat = Chat::message($message)
            ->from($partner)
            ->to($conversation)
            ->send();



        $time =   Carbon::parse($chat->created_at)->format('h:i');

        $this->NotificationToUser($chat);
        $this->sendNotificationFormPartner($request->user(), $message, $chat->participation_id);
        $this->RefreshChat($request->user()->id, $chat->conversation_id, $chat->body, $time);
        return $this->messageResponse('Done!');
    }
    /**
     * 
     *  Get Partner Conversation By Id
     *
     *  This endpoint allows you to get Partner Conversation By Id .
     * 
     * @bodyParam conversation_id string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function getPartnerConversationById(GetPartnerConvByIdRequest $request)
    {
        $validator = $request->validated();

        $partner = $request->user()->partner;

        $conversation = Chat::conversations()->getById($validator['conversation_id']);

        $readall = Chat::conversation($conversation)->setParticipant($partner)->readAll();
        $get = Chat::conversation($conversation)->setParticipant($partner);
        return  $this->handleResponse(true, $get->getMessages()->all());
    }
    /**
     * 
     *  Get Partner Conversations 
     *
     *  This endpoint allows you to get Partner Conversations .
     * 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function partnerConversations(Request $request)
    {
        $user = $request->user()->partner;

        if ($user->partner) {
            return $this->handleError("You Are Partner, Can't use this API");
        }
        $data = $user->conversations();

        foreach ($data as $value) {
            $user = $request->user()->partner;

            if ($user->partner) {

                $conversation = Chat::conversations()->getById($value->id);

                $count =  Chat::conversation($conversation)->setParticipant($user->partner)->unreadCount();

                $getMessage = Chat::conversation($conversation)->setParticipant($user)->getMessages();
                $lastMessage =  $getMessage->last()->body ?? null;
            } else {
                $conversation = Chat::conversations()->getById($value->id);

                $count = Chat::conversation($conversation)->setParticipant($user)->unreadCount();

                $getMessage = Chat::conversation($conversation)->setParticipant($user)->getMessages();
                $lastMessage =  $getMessage->last()->body ?? null;
            }
            $value->unread_count = $count;
            $value->last_message = $lastMessage;
        }
        return $data;
    }
    /**
     * 
     *  Conversation Readed 
     *
     *  This endpoint allows you to check if conversation readed .
     * @bodyParam conversation_id string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function isConversationRead(IsConversationReadRequest $request)
    {

        $validator = $request->validated();

        $user = $request->user();

        if ($user->partner) {

            $conversation = Chat::conversations()->getById($validator['conversation_id']);

            return  Chat::conversation($conversation)->setParticipant($user->partner)->unreadCount();
        } else {
            $conversation = Chat::conversations()->getById($validator['conversation_id']);

            return  Chat::conversation($conversation)->setParticipant($user)->unreadCount();
        }
    }
    /**
     * 
     *  Partner Sending Attachment
     *
     *  This endpoint allows you to send attachment by partner .
     * 
     * @bodyParam conversation_id string required . 
     * @bodyParam media  required . 
     * @bodyParam type string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function PartnerSendAttachment(UserSendAttachmentRequest $request)
    {
        $validator = $request->validated();

        $partner = Partner::find($validator['partner_id']);
        $conversation = Chat::conversations()->getById($validator['conversation_id']);


        //upload file

        $filenameWithExt = $request->file('media')->getClientOriginalName();
        //Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $request->file('media')->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
        // Upload Image
        $path = $request->file('media')->storeAs('public/chat', $fileNameToStore);

        $chat =   Chat::message('Attachment')
            ->type($validator['type'])
            ->data(['file_name' => $fileNameToStore, 'file_url' => 'storage/chat/' . $fileNameToStore])
            ->from($partner)
            ->to($conversation)
            ->send();

        $this->NotificationToUser($chat);
        $this->sendNotificationFormPartner($request->user(), 'User ' . $request->user()->name . ' Send Attachment', $chat->participation_id);
        return $this->messageResponse('Done!');
    }
    /**
     * 
     *  User Sending Attachment
     *
     *  This endpoint allows you to send attachment by user .
     * 
     * @bodyParam conversation_id string required . 
     * @bodyParam media  required . 
     * @bodyParam type string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function userSendAttachment(UserSendAttachmentRequest $request)
    {
        $validator = $request->validated();

        $user = $request->user();
        $conversation = Chat::conversations()->getById($validator['conversation_id']);


        //upload file

        $filenameWithExt = $request->file('media')->getClientOriginalName();
        //Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $request->file('media')->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
        // Upload Image
        $path = $request->file('media')->storeAs('public/chat', $fileNameToStore);

        $chat =   Chat::message('Attachment')
            ->type($validator['type'])
            ->data(['file_name' => $fileNameToStore, 'file_url' => 'storage/chat/' . $fileNameToStore])
            ->from($user)
            ->to($conversation)
            ->send();

        $this->NotificationToPartner($chat);
        $this->sendNotificationFormUser($request->user(), 'User ' . $request->user()->name . ' Send Attachment', $chat->participation_id);
        return $this->messageResponse('Done!');
    }

    private function NotificationToPartner($chat)
    {
        broadcast(new UserSendMessage($chat))->toOthers();
    }

    private function NotificationToUser($chat)
    {
        broadcast(new UserSendMessage($chat))->toOthers();
    }

    private function RefreshChat($user_id,  $conversation_id,  $body,  $time)
    {
        broadcast(new ChatRefresh($user_id,  $conversation_id,  $body,  $time))->toOthers();
    }

    private function sendNotificationFormUser($from, $body, $deviceTokens)
    {
        $partnerToken = User::find($deviceTokens)->device_token;
        return Larafirebase::withTitle($from->name)
            ->withBody($body)
            ->withSound('default')
            ->withAdditionalData([
                'color' => '#rrggbb',
                'badge' => 0,
            ])
            ->sendNotification([$partnerToken]);
    }
    private function sendNotificationFormPartner($from, $body, $deviceTokens)
    {
        $userToken = User::find($deviceTokens)->device_token;

        return Larafirebase::withTitle($from->partner->business_name)
            ->withBody($body)
            ->withSound('default')
            ->withAdditionalData([
                'color' => '#rrggbb',
                'badge' => 0,
            ])
            ->sendNotification([$userToken]);
    }
}
