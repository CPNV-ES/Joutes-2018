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


        // Retrieving all the "final" pools (where bestFinalRank is set), and for this participant only.
        $finalPools = DB::table('pools')
            //->whereNotNull('pools.bestFinalRank')
            ->join('contenders','pools.id','=','contenders.pool_id')
            ->join('teams','teams.id','=','contenders.team_id')
            ->join('participant_team','teams.id','=','participant_team.team_id')
            ->where('participant_team.participant_id',$idParticipant)
            ->get();


    $ranking = array();
$teamCount = 0;
$poolGap = 0;

        foreach ($finalPools as $pool)
        {
            // Best way I found to only add the rank on existing teams recording inside every pools (I tried to instead use foreach ($finalPools[$i] as $pool) but i can't. Will try to improve this later.
            if (isset($pool->name))
            {

                // Putting the rank for every team in every pools
                if ($pool->stage == '2') { $poolGap = 4;dd(); } //This is the gap between participants in a pool, depending on the stage of the pool
                if ($pool->stage == '3') { $poolGap = 2; }
                if ($pool->stage == '4') { $poolGap = 1; }
                // The final best is is the one in the DBB, and I add the poolGap*position of the team
                $ranking[$teamCount]['team'] = $pool->name;
                $ranking[$teamCount]['stage'] = $pool->stage;
                $ranking[$teamCount]['rank'] = $pool->bestFinalRank + ($poolGap * $teamCount);
                $teamCount++;
            }
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
