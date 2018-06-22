<?php

namespace App\Http\Controllers;

use App\Tournament;
use App\Team;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TournamentTeamController extends Controller
{
    /**
     * Display a listing of the tournaments.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Davide Carboni
     */
    public function index(Request $request , $id)
    {
        $tournament =  Tournament::findOrFail($id);
        $teams = $tournament->teams;

        $list = array();
        // return a list of teams for a selected tournament using ajax
        if ($request->ajax()) {
            $list = array();
            for ($i=0; $i < sizeof($teams); $i++) {
                if ($teams[$i]->isComplete() == false) // Only teams that still have availability
                    $list[$teams[$i]->id] = $teams[$i]->name;
            }
            return $list;
        }
    }

    /**
     * Display the specified tournament.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Davide Carboni
     */
    public function show(Request $request, $id)
    {
        //
    }


}
