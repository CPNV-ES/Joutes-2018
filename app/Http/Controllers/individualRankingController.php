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

            ->select('participants.id as participant_id', 'tournaments.id as tournament_id', 'tournaments.name as tournament_name','teams.name as team_name','games.contender1_id as contender_id','games.score_contender1 as score');

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

            ->select('participants.id as participant_id', 'tournaments.id as tournament_id', 'tournaments.name as tournament_name','teams.name as team_name','games2.contender2_id as contender_id','games2.score_contender2 as score');

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
                $test[$a]['tournament_name'] = $individualRanking[$i]->tournament_name;
                $test[$a]['team_name'] = $individualRanking[$i]->team_name;
                $test[$a]['contender_id'] = $individualRanking[$i]->contender_id;
                $totalScore += $individualRanking[$i]->score;
                $test[$a]['score'] = $totalScore;
            }
            else
            {
                $test[$a]['tournament_name'] = $individualRanking[$i]->tournament_name;
                $test[$a]['team_name'] = $individualRanking[$i]->team_name;
                $test[$a]['contender_id'] = $individualRanking[$i]->contender_id;
                $totalScore += $individualRanking[$i]->score;
                $test[$a]['score'] = $totalScore;
                ;
            }
            $oldTournament_name = $individualRanking[$i]->tournament_id;

        }
        dd ($test);




        $totalPoints = 0;
        $totalGamesWon = 0;
/*
        foreach ($individualRanking as $ranking)
        {

            // If the participant team is the "Contender 1" of the games
            if ($ranking->contender1_id == $idTeams)
            {
                $totalPoints = $individualRanking->sum('games.score_contender1');

                // If his team won against the other team, will count
                if ($ranking->score_contender1 > $ranking->score_contender2)
                {
                    $totalGamesWon++;
                }
                echo '</br>YES<br>';
            }

            // If the participant team is the "Contender 2" of the games
            else if ($ranking->contender2_id == $idParticipant)
            {
                $totalPoints += $individualRanking->sum('games.score_contender2');
                if ($ranking->score_contender2 > $ranking->score_contender1)
                {
                    $totalGamesWon++;
                }
                echo 'YES 2<br>';
            }
        }
*/


        echo '<div style="margin-left:300px;">';
        var_dump ($individualRanking);
        echo '</div>';

        return view('individualRanking.index')->with('ranking',$individualRanking);
    }






    public function destroy($id)
    {
        //
    }
}
