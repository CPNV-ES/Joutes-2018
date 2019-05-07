<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Game;
use App\Pool;

class TournamentPoolGameController extends Controller
{
    /**
     * Update the score
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function update(Request $request, $tournamentId, $poolId, $gameId)
    {

        $game = Game::find($gameId);

        //edit match score
        if($request->score1 !== null){

            $game->score_contender1 = $request->score1;
            $game->score_contender2 = $request->score2;
            $game->save();

            $pool = Pool::find($poolId);
            $rankings = $pool->rankings();

            return json_encode($rankings);

        } //shift time (decalage match)
        else if(!empty($request->newTime)){

            $game->start_time = $request->newTime;
            $game->save();

        }

    }

}
