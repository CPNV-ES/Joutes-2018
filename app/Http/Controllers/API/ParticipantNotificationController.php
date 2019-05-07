<?php

namespace App\Http\Controllers\API;


use App\Participant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Response\Transformers\NotificationTransformer;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ParticipantNotificationController  extends Controller {

    public function index(Request $request, $participant_id) {

        return $this->response->collection(Participant::find($participant_id)->newNotifications(), new NotificationTransformer,  ['key' => 'notifications']);
    }
}
