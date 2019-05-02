<?php

namespace App\Http\Controllers\API;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Response\Transformers\ParticipantTransformer;
use App\Http\Response\Transformers\SingleParticipantTransformer;

class EventParticipantController extends Controller {


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param int $event_id
     * @return \Illuminate\Http\Response
     *
     * @author Doran Kayoumi
     */
    public function index(Request $request, $event_id) {

        // get event teams
        $participants = Event::findOrFail($event_id)->participants();

        return $this->response->collection($participants, new ParticipantTransformer, ['key' => 'participants']);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  int  $event_id
     * @param  int  $participant_id
     * @return \Illuminate\Http\Response
     *
     * @author Doran Kayoumi
     */
    public function show(Request $request, $event_id, $participant_id) {
        // get wanted participant
        $participant = Event::findOrFail($event_id)->participant($participant_id);

        return $this->response->item($participant, new SingleParticipantTransformer, ['key' => 'participant']);
    }
}
