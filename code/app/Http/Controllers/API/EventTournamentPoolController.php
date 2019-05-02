<?php

namespace App\Http\Controllers\API;

use App\Event;
use App\Http\Response\Transformers\TournamentPoolTransformer;
use App\Tournament;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Response\Transformers\SinglePoolTransformer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventTournamentPoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $tournament_id) {
        // check if it's an api request
        if ($request->is('api/*'))
        {
            // get tournament pools
            $pools = Tournament::findOrFail($tournament_id)->pools;

            return $this->response->collection($pools, new TournamentPoolTransformer, ['key' => 'pools']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @param  int  $event_id
     * @param  int  $tournament_id
     * @param  int  $pool_id
     * @return \Illuminate\Http\Response
     *
     * @author Struan Forsyth
     */
    public function show(Request $request, $event_id, $tournament_id, $pool_id) {
        // check is it's an api request
        if ($request->is('api/*')) {

            $pool = Event::findOrFail($event_id)->tournament($tournament_id)->pool($pool_id);
            return $this->response->item($pool, new SinglePoolTransformer, ['key' => 'pool']);
        }

        return true;
    }
}
