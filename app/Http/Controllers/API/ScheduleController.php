<?php

namespace App\Http\Controllers\API;

use App\Game;
use App\Pool;
use App\Tournament;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Response\Transformers\ScheduleTransformer;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleController extends Controller {

    public function index(Request $request, $tournament_id) {

        return $this->response->collection(Tournament::find($tournament_id)->GetActiveGames($request->nb_matches), new ScheduleTransformer);
    }
}
