<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Response\Transformers\TournamentTransformer;
use App\Http\Response\Transformers\SingleTournamentTransformer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventTournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @param int $event_id
     * @return \Illuminate\Http\Response
     *
     * @author Doran Kayoumi
     * @edit Davide Carboni
     */
    public function index(Request $request, $event_id) {
        // check is it's an api request
        if ($request->is('api/*')) {
            // get event tournaments
            $tournaments = Event::findOrFail($event_id)->tournaments;

            return $this->response->collection($tournaments, new TournamentTransformer, ['key' => 'tournaments']);
        }

        $event = Event::findOrFail($event_id);
        $tournaments = $event->tournaments;

        // return a list of tournaments for a selected event using ajax
        if ($request->ajax()) {
            $list = array();
            for ($i=0; $i < sizeof($tournaments); $i++) {
                if ($tournaments[$i]->isComplete() == false) { // tournament complete
                    $list[] = ['id' => $tournaments[$i]->id, 'name' => $tournaments[$i]->name, 'start_date' => $tournaments[$i]->start_date, 'end_date' => $tournaments[$i]->end_date];
                    //$list[$tournaments[$i]->id] = $tournaments[$i]->name;
                }
            }
            return $list;
        }

        $event = Event::findOrFail($event_id);
        $tournaments = $event->tournaments;

        foreach ($tournaments as $tournament) {
            if (empty($tournament->img)) {
                $tournament->img = 'default.jpg';
            }
        }

        return view('tournament.index', array(
            "tournaments" => $tournaments,
            "fromEvent" => true,
            "event" => $event
        ));

    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @param  int  $event_id
     * @param  int  $tournament_id
     * @return \Illuminate\Http\Response
     *
     * @author Doran Kayoumi
     */
    public function show(Request $request, $event_id, $tournament_id) {
        // check is it's an api request
        if ($request->is('api/*')) {

            $tournament  = Event::findOrFail($event_id)->tournament($tournament_id);

            return $this->response->item($tournament, new SingleTournamentTransformer, ['key' => 'tournament']);
        }

        return true;
    }
}
