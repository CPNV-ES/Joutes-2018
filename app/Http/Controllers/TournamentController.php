<?php

namespace App\Http\Controllers;

use App\Tournament;
use App\Pool;
use App\Team;
use App\News;
use App\Participant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TournamentController extends Controller
{
    /**
     * Display a listing of the tournaments.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function index()
    {
        $tournaments = Tournament::all()->sortBy("start_date");

        foreach ($tournaments as $tournament) {
            if (empty($tournament->img)) {
                $tournament->img = 'default.jpg';
            }
        }

        return view('tournament.index', array(
            "tournaments" => $tournaments,
            "fromEvent" => false
        ));
    }

    /**
     * Display the specified tournament.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc, Davide Carboni
     */
    public function show(Request $request, $id)
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

        $news = \App\News::where('tournament_id', $id)
                            ->OrderBy('creation_datetime', 'desc')
                            ->get();
        $teams = \App\Team::where('tournament_id', $id)->get();


        foreach ($teams as $team)
        {
            $listTeams[] = $team['id'];

        }


        $participants_teams = \App\Team::find(1)->Participants();

        //foreach ($participants_teams->Participant as $each_participants)
        //{
        //    $participants[] = $each_participants;
        //}
        //echo $participants_teams[0]['participants'][0]['first_name'];


        for ($i=0;$i<'';$i++)
        {
            $test[] = $participants_teams[$i]['participants'];
            //$participantsName[] = $participants[$i]['first_name'];
        }

        //dd($participants_teams);

        return view('tournament.show')->with('tournament', $tournament)
                                      ->with('pools', $pools)
                                      ->with('news', $news)
                                      //->with('participants', $participants)
                                      ->with('totalStage', $totalStage);
    }
}
