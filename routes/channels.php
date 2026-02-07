<?php

use Illuminate\Support\Facades\Broadcast;
use Musonza\Chat\Facades\ChatFacade as Chat;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.{id}', function ($user, $id) {
    $conversation = Chat::conversations()->getById($id);

    if ($user->partner) {
        $participant =  Chat::conversation($conversation)->getParticipation($user->partner->id);
        return $participant != null;
    } else {
        $participant =  Chat::conversation($conversation)->getParticipation($user->id);
        return $participant != null;
    }
});
