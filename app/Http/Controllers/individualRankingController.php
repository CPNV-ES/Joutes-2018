<?php

namespace App\Http\Controllers;

use App\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class individualRankingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idParticipant = 1;

        /* The where clause is not respected. I don't know why. I will use other tables to acces the datas I need
        // Retrieving all the "final" pools (where bestFinalRank is set), and for this participant only.
        $finalPools = DB::table('pools')
            ->whereNotNull('pools.bestFinalRank')
            ->join('tournaments','tournaments.id','=','pools.tournament_id')
            ->join('teams','tournaments.id','=','teams.tournament_id')
            ->join('participant_team','teams.id','=','participant_team.team_id')
            ->where('participant_team.participant_id',$idParticipant)
            ->select('tournaments.name as tournament', 'teams.name as team', 'pools.stage', 'pools.bestFinalRank')
            ->get();
        */

        $finalPools = DB::table('pools')
            // Should not be in comment, only for dev purpose
            //->whereNotNull('pools.bestFinalRank')
            ->join('contenders','pools.id','=','contenders.pool_id')
            ->join('teams','teams.id','=','contenders.team_id')
            ->join('participant_team','teams.id','=','participant_team.team_id')
            ->where('participant_team.participant_id',$idParticipant)
            ->join('tournaments','tournaments.id','=','pools.tournament_id')
            ->select('pools.id as pool_id','tournaments.name as tournament', 'teams.name as team', 'pools.stage', 'pools.bestFinalRank')
            ->get();


        //Store the ranking of the participant
        $ranking = array();

        $poolRank = 0;
        $nbPoolsInStage = 0;
        $previousPool = 0;

        foreach ($finalPools as $pool)
        {
            // The rank of the team of the participants is reset every time the function "execute" a new pool.
            // This is done with the $previousPool var, which store the id of the pool, but a the end of the loop, so the previous one here.
            if ($pool->pool_id != $previousPool)
            {
                $poolRank = 0;
            }

            //This is the gap between participants in a pool, depending on the stage of the pool
            if ($pool->stage == '2') { $nbPoolsInStage = 4; }
            if ($pool->stage == '3') { $nbPoolsInStage = 2; }
            if ($pool->stage == '4') { $nbPoolsInStage = 1; }

            $ranking[$poolRank]['tournament'] = $pool->tournament;
            $ranking[$poolRank]['team'] = $pool->team;
            $ranking[$poolRank]['stage'] = $pool->stage;
            // only used in dev, because in reality this value should be always set to 1, according to the request
            if ($pool->bestFinalRank != 0)
            {
                // The rank is calculated with the bestFinalRank value, which is equal to the rank of the first of the current pool. We add to this the rank of the team in pool multiply  by the number of pools in this stage
                $ranking[$poolRank]['rank'] = $pool->bestFinalRank + ($nbPoolsInStage * $poolRank);
            }
            else
            {
                $ranking[$poolRank]['rank'] = 'Pas terminé';
            }
            $previousPool = $pool->pool_id;
            $poolRank++;

        }

        // Used to sort the array by the rank index value.
        foreach ($ranking as $key => $row)
        {
            $vc_array_name[$key] = $row['rank'];
        }
        array_multisort($vc_array_name, SORT_ASC, $ranking);

        return view('individualRanking.index')->with('ranking',$ranking);
    }






    public function destroy($id)
    {
        //
    }
}
