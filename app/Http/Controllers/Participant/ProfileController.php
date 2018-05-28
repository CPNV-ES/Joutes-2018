<?php

namespace App\Http\Controllers\Participant;

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
        return view('profile.index')->with('teams', $teams);
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

    public function show()
    {

    }

    public function destry()
    {

    }
}
