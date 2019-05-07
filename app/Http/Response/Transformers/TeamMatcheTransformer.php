<?php
namespace App\Http\Response\Transformers;

use App\Game;
use League\Fractal\TransformerAbstract;

class TeamMatcheTransformer extends TransformerAbstract
{
    public function transform(Game $game)
    {
        $team1 = $game->team1();
        $team2 = $game->team2();

        if ($team1->id == $game->team_id) {
            $ownScore = $game->score_contender1;
            $opponentScore = $game->score_contender2;
            $ownName = $team1->name;
            $opponentName = $team2->name;
        } elseif ($team2->id == $game->team_id) {
            $ownScore = $game->score_contender2;
            $opponentScore = $game->score_contender1;
            $ownName = $team2->name;
            $opponentName = $team1->name;
        }

        return [
            'id'            => (int) $game->id,
            'idPool'        => (int) $game->pool()->id,
            'ownScore'      => (int) $ownScore,
            'opponentScore' => (int) $opponentScore,
            'ownName'       => (string) $ownName,
            'opponentName'  => (string) $opponentName,
            'startTime'     => $game->start_time,
            'isFinished'    => (bool) ($ownScore != 0 && $opponentScore != 0)
        ];
    }
}
