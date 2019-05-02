<?php

namespace App\Http\Controllers\API;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Response\Transformers\TeamTransformer;
use App\Http\Response\Transformers\SingleTeamTransformer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class EventTeamController extends Controller
{
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
        // check is it's an api request
        if ($request->is('api/*')) {

            // get event tournaments
            $teams = Event::findOrFail($event_id)->teams;

            return $this->response->collection($teams, new TeamTransformer, ['key' => 'teams']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  int  $event_id
     * @param  int  $team_id
     * @return \Illuminate\Http\Response
     *
     * @author Doran Kayoumi
     */
    public function show(Request $request, $event_id, $team_id) {

        // check is it's an api request
        if ($request->is('api/*')) {

            // find event and team with given ids
            $team = Event::findOrFail($event_id)->team($team_id);

            return $this->response->item($team, new SingleTeamTransformer, ['key' => 'team']);
        }
    }
}
