<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TournamentSetup;

class TournamentSetupController extends Controller
{
    public function update(Request $request, $tournamentId) {
        TournamentSetup::update($tournamentId);
    }
}
