<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tournament;

class ScheduleController extends Controller {

    public function index($tournament) {

        return view('schedule.index', array(
            'tournament' => Tournament::find($tournament)
        ));
    }
}
