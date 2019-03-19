<?php

namespace App\Http\Controllers\Admin;

use App\Pool;
use App\Contender;
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
    public function create()
    {
        return view('pool.create');
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
