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
        $idParticipant = 3;
        $idTeams = 2;

        // Retrieve all datas needed when the participant team is the contender 1
        $individualRankingContender1 = DB::table('pools')
            ->join('tournaments','tournaments.id','=','pools.tournament_id')
            ->join('teams','tournaments.id','=','teams.tournament_id')
            ->join('participant_team','teams.id','=','participant_team.team_id')
            ->join('participants','participants.id','=','participant_team.participant_id')
            ->where('participants.id','=',$idParticipant)
            ->where('contenders.team_id','=',$idTeams)
            ->join('contenders','pools.id','=','contenders.pool_id')
            ->join('games','contenders.id','=','games.contender1_id')

            ->select('participants.id as participant_id', 'participants.first_name as participant_first_name', 'participants.last_name as participant_last_name', 'tournaments.id as tournament_id', 'tournaments.name as tournament_name','teams.name as team_name','games.contender1_id as contender_id','games.score_contender1 as score');

        // Retrieve all datas needed when the participant team is the contender 2
        $individualRankingContender2 = DB::table('pools')
            ->join('tournaments','tournaments.id','=','pools.tournament_id')
            ->join('teams','tournaments.id','=','teams.tournament_id')
            ->join('participant_team','teams.id','=','participant_team.team_id')
            ->join('participants','participants.id','=','participant_team.participant_id')
            ->where('participants.id','=',$idParticipant)
            ->where('contenders.team_id','=',$idTeams)
            ->join('contenders','pools.id','=','contenders.pool_id')
            ->join('games as games2','contenders.id','=','games2.contender2_id')

            ->select('participants.id as participant_id', 'participants.first_name as participant_first_name', 'participants.last_name as participant_last_name', 'tournaments.id as tournament_id', 'tournaments.name as tournament_name','teams.name as team_name','games2.contender2_id as contender_id','games2.score_contender2 as score');

        // I merge the two collections. So that i have every match played by the team of the participant.
        $individualRanking = $individualRankingContender1->get()->merge($individualRankingContender2->get());


        $oldTournament_name = 'a';
        $a = 0;
        $test = array();
        $totalScore = 0;
        for ($i=0;$i<sizeof($individualRanking);$i++)
        {
            if ($individualRanking[$i]->tournament_id != $oldTournament_name)
            {
                $a++;
            }

            $test[$a]['tournament_name'] = $individualRanking[$i]->tournament_name;
            $test[$a]['participant_name'] = $individualRanking[$i]->participant_first_name.' '.$individualRanking[$i]->participant_last_name;
            $test[$a]['team_name'] = $individualRanking[$i]->team_name;
            $test[$a]['contender_id'] = $individualRanking[$i]->contender_id;
            $totalScore += $individualRanking[$i]->score;
            $test[$a]['score'] = $totalScore;

            $oldTournament_name = $individualRanking[$i]->tournament_id;

        }
        var_dump ($test);
        echo '</div>';

        return view('individualRanking.index')->with('ranking',$test);
    }






    public function destroy($id)
    {
        //
    }
}
