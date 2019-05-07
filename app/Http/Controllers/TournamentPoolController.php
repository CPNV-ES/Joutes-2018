<?php

namespace App\Http\Controllers;

use App\Pool;
use Illuminate\Http\Request;

class TournamentPoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idTournament, $idPool)
    {
        $pool = Pool::find($idPool);
        $games = $pool->games->sortBy("start_time");

        $rankings = $pool->rankings();

        $ranking_completed = true;
        foreach ($rankings as $ranking) {
            if ($ranking["team_id"] == -1) {
                $ranking_completed = false;
                break;
            }
        }

        $games_completed = true;
        foreach ($games as $game) {
            if ($game->score_contender1 === null || $game->score_contender2 === null) {
                $games_completed = false;
                break;
            }
        }

        // return view('pool.show')->with('pool', $pool)->with('games', $games);
        return view('pool.show', array(
            "pool"              => $pool,
            "games"             => $games,
            "ranking_completed" => $ranking_completed,
            "games_completed"   => $games_completed
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
