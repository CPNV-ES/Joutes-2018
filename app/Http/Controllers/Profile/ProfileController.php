<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Team;
use App\Participant;
use Auth;

class ProfileController extends Controller
{
    /*
     * Display the current profile
     */

    public function index()
    {
        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;
        return view('profile.index')->with('teams', $teams)->with('participant', $participant) ;
    }

    public function update()
    {
        return view('profile.index');
    }

    public function create(Request $request)
    {

    }

    public function store(Request $request)
    {

    }

    public function show(Request $request, $id)
    {
        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;
        return view('team.index', array(
            "teams" => $teams,
        ));

    }

    public function destroy()
    {

    }

}
