<?php

namespace App\Http\Controllers;

use App\Tournament;
use App\Pool;
use App\Team;

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
     * @author Dessaules Loïc
     */
    public function show(Request $request, $id)
    {
        $tournament = Tournament::find($id);

        if ($request->ajax())
        {
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
        return view('tournament.show')->with('tournament', $tournament)
                                      ->with('pools', $pools)
                                      ->with('totalStage', $totalStage);
    }
}
