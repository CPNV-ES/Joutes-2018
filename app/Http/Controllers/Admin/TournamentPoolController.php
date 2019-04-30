<?php

namespace App\Http\Controllers\Admin;

use App\Pool;
use App\PoolMode; // This is the linked model
use App\Contender;
use App\Tournament;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TournamentPoolController extends Controller
{
    /**
     * Show the form for creating a pool.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Jérémy Gfeller
     */
    public function index(Request $request, $id)
    {
        $tournament = Tournament::find($id);

        if ($request->ajax())
        {
            // Check if the tornament is Full, no more teams are accepted
            if ($request->input("isFull") == "isFull") {
                if (($tournament->isComplete()) || ($tournament == null)) return 1;
                else return 0;
            }
        }

        $pools = $tournament->pools;
        $totalStage = 0;
        foreach ($pools as $pool) {
            if($pool->stage > $totalStage){
                $totalStage = $pool->stage;
            }
        }

        $poolModes = PoolMode::all();
        return view('pool.index')->with('pools', $poolModes)
                                    ->with('tournament', $tournament)
                                    ->with('pools', $pools)
                                    ->with('totalStage', $totalStage);
    }

    /**
     * Store data in the DB.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Jérémy Gfeller
     */
    public function store(Request $request)
    {
        $pool = new Pool;
        $pool->poolName = "$request->poolName";
        $pool->stage = $request->stage;
        $pool->poolSize = $request->pool;
        $pool->isFinished = $request->isFinished;
        $pool->tournament_id = request()->route()->parameters['tournament'];
        $pool->mode_id = 1;
        $pool->game_type_id = 1;
        $pool->save();
        return redirect()->route('tournaments.pools.index', request()->route()->parameters['tournament']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $tournament_id
     * @param  int  $pool_id
     * @return \Illuminate\Http\Response
     *
     * @author Doran Kayoumi
     */
    public function update(Request $request, $tournament_id, $pool_id) {
        // get pool, set it to finished and save changes
        $pool = Pool::find($pool_id);
        $pool->isFinished = 1;
        $pool->save();

        // find contender for the next pool with the current rank and current pool and set it with the team id
        $contender = Contender::where('pool_from_id', $pool_id)->where('rank_in_pool', $request->rank_in_pool)->firstOrFail();
        $contender->team_id = $request->team_id;
        $contender->save();

        // ajax will go into success
        return "{}";
    }
}