<?php
namespace App\Http\Response\Transformers;

use App\Game;
use App\Team;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class ScheduleTransformer extends TransformerAbstract
{

    public function transform(Game $game) {

        return [
            'id'        => (int)    $game->id,
            'teams'     => [
                $game->team1()->name,
                $game->team2()->name,
            ],
            'court'     => (string) $game->court->acronym,
            'sport'     => (string) $game->court->sport->name,
            'date'      => (string) $game->date,
            'time'      => (string) Carbon::parse($game->start_time)->format('H:i'),
        ];
    }

}
