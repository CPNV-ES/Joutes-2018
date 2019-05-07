<?php
namespace App\Http\Response\Transformers;

use App\Team;
use League\Fractal\TransformerAbstract;

class TournamentTeamTransformer extends TransformerAbstract
{
    public function transform(Team $team) {

        return [
            'id'   => $team->id,
            'name' => $team->name,
        ];
    }
}
