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
        $currentUserId = Auth::user()->id;
        $currentPartecipant = Participant::where('user_ID', $currentUserId)->get()->first();
        $teams = Participant::find($currentPartecipant->id)->teamss;
        return view('profile.index')->with('teams', $teams);
    }

    public function update()
    {
        return view('profile.index');
    }
}
