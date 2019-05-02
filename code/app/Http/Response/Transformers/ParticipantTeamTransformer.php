<?php
namespace App\Http\Response\Transformers;

use App\Team;
use League\Fractal\TransformerAbstract;

class ParticipantTeamTransformer extends TransformerAbstract
{
    public function transform(Team $teams) {

        return [
            'id'            => (int) $teams->id,
            'name'          => (string) $teams->name,
            'tournament_id' => (int) $teams->tournament->id,
        ];
    }
}
