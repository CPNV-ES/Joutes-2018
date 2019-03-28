<?php

namespace App\Http\Controllers\Admin;

use App\Pool;
use App\PoolMode; // This is the linked model
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
    public function index()
    {
        $poolModes = PoolMode::all();
        return view('pool.index')->with('pools', $poolModes);
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
        // pool, start_hour, time_match, nb_team
        $this->elimination_direct($request->start_hour, $request->pool);
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

    private function elimination_direct($start_hour, $pool_id)
    {
        echo "================================================================================================================\n";
        echo "Tennis\n";
        echo "================================================================================================================\n";
        $bv = \App\Tournament::where('name', 'like', '%Tennis%')->where('start_date', '>=', '2019-01-01')->first();
        if (!$bv)
        {
            echo "Le tournoi de tennis n'existe pas\n";
            return;
        }
        $tournamentid = $bv->id;
        $sportid = $bv->sport_id;

        $teams = \App\Team::where('tournament_id', '=', $tournamentid)->get();

        echo "Tournoi #$tournamentid, " . $teams->count() . " équipes inscrites\n";
        echo "Elimination directe\n";

        for ($stage = 1; $stage <= 5; $stage++)
        {
            echo "Tour $stage, ".pow (2,5-$stage)." matches ";
            for ($pooln = 1; $pooln <= pow (2, 5-$stage); $pooln++)
            {
                echo ".";
                $pool = new \App\Pool([
                    'tournament_id' => $tournamentid,
                    'start_time' => $start_hour,
                    'end_time' => '16:00',
                    'poolName' => "Game $stage$pooln",
                    'mode_id' => $pool_id,
                    'game_type_id' => 1,
                    'poolSize' => 2,
                    'stage' => $stage,
                    'isFinished' => 0
                ]);
                $pool->save();
                if (!isset($firstpoolStage1)) $firstpoolStage1 = $pool->id; // we'll need that to put teams into pools
            }
            echo "\n";
        }

        echo "OK\nInscription des équipes ... ";

        $pooln = 0;
        foreach ($teams as $team)
        {
            $contender = new \App\Contender([
                'pool_id' => $firstpoolStage1+intdiv($pooln++,2),
                'team_id' => $team->id
            ]);
            $contender->save();
            if (!isset($firstcontender)) $firstcontender = $contender->id; // we'll need that to put teams into pools
        }

        echo "OK\nLiens entre poules ... ";
        $nbpoolstoadd = 8;
        $pooldelta = 16;
        $poolfromdelta = 0;

        while ($nbpoolstoadd > 0)
        {
            for ($pooln = 0; $pooln < $nbpoolstoadd; $pooln++)
            {
                $contender = new \App\Contender([
                    'pool_id' => $firstpoolStage1+$pooldelta+$pooln,
                    'pool_from_id' => $firstpoolStage1+$poolfromdelta+$pooln*2,
                    'rank_in_pool' => 1
                ]);
                $contender->save();
                $contender = new \App\Contender([
                    'pool_id' => $firstpoolStage1+$pooldelta+$pooln,
                    'pool_from_id' => $firstpoolStage1+$poolfromdelta+$pooln*2+1,
                    'rank_in_pool' => 1
                ]);
                $contender->save();
            }
            $poolfromdelta = $pooldelta;
            $pooldelta += $nbpoolstoadd;
            $nbpoolstoadd = intdiv($nbpoolstoadd,2);
        }
        
        $firstcourt = \App\Court::where('sport_id', '=', $sportid)->first()->id;

        echo "OK\nMatches ... ";

        for ($matchnumber = 0; $matchnumber < 31; $matchnumber++)
            (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:30', 'contender1_id' => $firstcontender+$matchnumber*2, 'contender2_id' => $firstcontender+$matchnumber*2 + 1, 'court_id' => $firstcourt]))->save();

        echo "OK\nTerminé.\n\n";

    }
}
