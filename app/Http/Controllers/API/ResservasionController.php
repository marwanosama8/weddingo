<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\ChangeRessStatusRequest;
use App\Http\Requests\StoreResservasionRequest;
use App\Http\Requests\UpdateResservasionRequest;
use App\Http\Resources\PartnerResservasionsResource;
use App\Http\Resources\UserResservasionsResource;
use App\Models\Partner;
use App\Models\PartnerPriceList;
use App\Models\Resservasion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Musonza\Chat\Facades\ChatFacade as Chat;
use Kutia\Larafirebase\Facades\Larafirebase;

/**
 * @group Resservation
 *
 * APIs for Resservation Module
 */
class ResservasionController extends Controller
{
    use Response;
    /**
     * 
     *  Show User Resservasions
     *
     *  This endpoint allows you to Show User Resservasions .
     * 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function userShowResservasion(Request $request)
    {
        $user = $request->user();

        if (count($user->resservasions)) {
            return UserResservasionsResource::collection($user->resservasions);
        } else {
            return $this->messageResponse("You Don't Have Any Resservasions!");
        }
    }

    /**
     * 
     *  Make Resservasions
     *
     *  This endpoint allows you to get make resservasions .
     * 
     * @bodyParam partner_id integer required . 
     * @bodyParam services_id integer required . 
     * @bodyParam date_time string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function makeResservasion(StoreResservasionRequest $request)
    {
        $validator = $request->validated();

        $user = $request->user();
        $partner = Partner::find($validator['partner_id']);
        $priceListTotal = PartnerPriceList::whereIn('id', $validator['services_id'])->pluck('price')->sum();
        $date = Carbon::createFromFormat('Y-m-d H:i', $validator['date_time']);

        // check if user ress with partner in this day 
        $now = Carbon::now()->format('Y-m-d');
        $check = $user->resservasions->where('partner_id', $partner->id)->where('created_at', '>=', $now)->where('status', 'canceled')->first();
        if ($check) {
            return $this->messageResponse("You have resservasion with this partner already!");
        }
        // check if date is not weekend for partner
        $checkIsWeekend = $this->checkIsWeekend($partner, $date);

        if ($checkIsWeekend) {
            return $this->messageResponse('This Day Are Not Avalliable, Please Choose Another Day!');
        }
        if ($checkIsWeekend == 2) {
            return $this->messageResponse("You Can't have resservasion with partner right now, Sorry!");
        }
        // store the resservasion
        $resservasion = new Resservasion();

        $resservasion->user_id = $user->id;
        $resservasion->partner_id = $partner->id;
        $resservasion->status = "waiting for approve";
        $resservasion->date_time = $date;
        $resservasion->total_price = $priceListTotal;

        $resservasion->save();


        // attach pricelist to resservasion
        $resservasion->resservasionPriceLists()->attach($validator['services_id']);

        //create chat between partner and user

        $conversation_id = $this->makeConversation($partner->id, $user->id);

        $msg = [
            "message" => "Resservasion Add!",
            "conversation_id" => $conversation_id,
        ];

        return $this->successEnvelope($msg);
    }

    /**
     * 
     *  Cancling Resservasions 
     *
     *  This endpoint allows you to Cancling Resservasions  .
     * 
     * @bodyParam resservasion_id integer required . 
     * @bodyParam partner_id integer required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */

    public function userCanelResservasion(ChangeRessStatusRequest $request)
    {
        // change ress. status
        $validator = $request->validated();
        Resservasion::find($validator['resservasion_id'])->update([
            'status' => 'canceled'
        ]);
        // send message to partner about cancelling
        $this->userCancelSendMessage($validator['partner_id'], $request->user()->id);
        $this->sendNotificationFormUser($request->user(), "User " . $request->user()->name . " has been canceled the resservasion");
        return $this->messageResponse('Canceled');
    }
    /**
     * 
     *  User Done Resservasion 
     *
     *  This endpoint allows you to make user done the resservastion  .
     * 
     * @bodyParam resservasion_id integer required . 
     * @bodyParam partner_id integer required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */

    public function userResservasionDone(ChangeRessStatusRequest $request)
    {
        // change ress. status
        $validator = $request->validated();
        Resservasion::find($validator['resservasion_id'])->update([
            'status' => 'done'
        ]);
        // send message to partner about cancelling
        $this->userDoneSendMessage($validator['partner_id'], $request->user()->id);
        $this->sendNotificationFormUser($request->user(), "User " . $request->user()->name . " has been done the resservasion");
        return $this->messageResponse('Done!');
    }

    /**
     * 
     *  Partner Resservasions
     *
     *  This endpoint allows you to get Partner Resservasions .
     * 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function partnerShowResservasions(Request $request)
    {
        $partner = $request->user()->partner;
        if (!$partner) {
            return $this->handleError('You are not partner!!!!!');
        } else {
            if (!count($partner->resservasions)) {
                return $this->messageResponse("You don't have resservasion request yet!");
            } else {
                $partner = $request->user()->partner;
                return PartnerResservasionsResource::collection($partner->resservasions);
            }
        }
    }
    /**
     * 
     *  Partner Accept Resservasions 
     *
     *  This endpoint allows you to get Partner Accept Resservasions .
     * 
     * @bodyParam resservasion_id integer required . 
     * @bodyParam user_id integer required . 
     * @bodyParam partner_id integer required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */

    public function partnerAcceptResservasion(ChangeRessStatusRequest $request)
    {
        $validator = $request->validated();

        // change ress status 

        Resservasion::find($validator['resservasion_id'])->update([
            'status' => 'approved'
        ]);

        // send message to user about accepting the ress
        $this->partnerSendMessage($validator['partner_id'], $validator['user_id'], false);
        $this->sendNotificationFormPartner($request->user(), "Partner " . $request->user()->partner->business_name . " has been accepted the resservasion");
        return $this->messageResponse('done');
    }
    /**
     * 
     *  Partner Refuse Resservasion
     *
     *  This endpoint allows you to Partner Refuse Resservasion .
     * 
     * @bodyParam resservasion_id integer required . 
     * @bodyParam user_id integer required . 
     * @bodyParam partner_id integer required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function partnerRefuseResservasion(ChangeRessStatusRequest $request)
    {
        $validator = $request->validated();

        // change ress status 

        Resservasion::find($validator['resservasion_id'])->update([
            'status' => 'decapproved'
        ]);

        // send message to user about accepting the ress
        $this->partnerSendMessage($validator['partner_id'], $validator['user_id']);
        $this->sendNotificationFormPartner($request->user(), "Partner " . $request->user()->partner->business_name . " has been refused the resservasion");
        return $this->messageResponse('done');
    }
    /**
     * 
     *  Partner show Resservasion Details
     *
     *  This endpoint allows you to Partner show Resservasion Details .
     * 
     * @bodyParam resservasion_id integer required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function partnerShowResservasionDetails(ChangeRessStatusRequest $request)
    {
        $validator = $request->validated();

        $ress = Resservasion::find($validator['resservasion_id']);
        return $this->successEnvelope($ress);
    }

    private function checkIsWeekend($partner, $date)
    {
        $no = 0;
        $week = $partner->weekends ?? array();
        $newDate = Carbon::createFromTimeString($date)->format('l');
        if (in_array($newDate, $week)) {
            return $no = 1;
        } else {
            return $no = 0;
        }
        if ($partner->weekends == null) {
            return $no = 2;
        }
    }

    private function makeConversation($partner_id, $user_id)
    {
        $user = User::find($user_id);
        $partner = Partner::find($partner_id);

        $participants = [$user, $partner];
        $haveOne = Chat::conversations()->between(...$participants);

        if ($haveOne) {
            return $haveOne->id;
        } else {
            $conversation = Chat::createConversation($participants, ['user' => $user->name, "partner" => $partner->business_name])->makePrivate(false);
            return $conversation->id;
        }
    }

    private function userCancelSendMessage($partner_id, $user_id)
    {
        $user = User::find($user_id);
        $partner = Partner::find($partner_id);
        $participants = [$user, $partner];

        $haveOne = Chat::conversations()->between(...$participants);
        $conversation = Chat::conversations()->getById($haveOne->id);

        $message = "User " . $user->name . " has been canceled the resservasion";

        Chat::message($message)
            ->from($user)
            ->to($conversation)
            ->send();
    }
    private function userDoneSendMessage($partner_id, $user_id)
    {
        $user = User::find($user_id);
        $partner = Partner::find($partner_id);
        $participants = [$user, $partner];

        $haveOne = Chat::conversations()->between(...$participants);
        $conversation = Chat::conversations()->getById($haveOne->id);

        $message = "User " . $user->name . " has been done the resservasion";

        Chat::message($message)
            ->from($user)
            ->to($conversation)
            ->send();
    }

    private function partnerSendMessage($partner_id, $user_id, $refuse = true)
    {
        $user = User::find($user_id);
        $partner = Partner::find($partner_id);
        $participants = [$user, $partner];

        $haveOne = Chat::conversations()->between(...$participants);
        $conversation = Chat::conversations()->getById($haveOne->id);

        if ($refuse) {
            $message = "Partner " . $partner->business_name . " has been refused the resservasion";
        } else {
            $message = "Partner " . $partner->business_name . " has been accepted the resservasion";
        }

        Chat::message($message)
            ->from($partner)
            ->to($conversation)
            ->send();
    }

    private function sendNotificationFormUser($from, $body)
    {
        return Larafirebase::withTitle($from->name)
            ->withBody($body)
            ->withSound('default')
            ->withAdditionalData([
                'color' => '#rrggbb',
                'badge' => 0,
            ])
            ->sendNotification($from->device_token);
    }
    private function sendNotificationFormPartner($from, $body)
    {
        return Larafirebase::withTitle($from->partner->business_name)
            ->withBody($body)
            ->withSound('default')
            ->withAdditionalData([
                'color' => '#rrggbb',
                'badge' => 0,
            ])
            ->sendNotification($from->device_token);
    }
}
