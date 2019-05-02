<?php
namespace App\Http\Response\Transformers;

use App\Tournament;
use League\Fractal\TransformerAbstract;

class TeamTournamentTransformer extends TransformerAbstract
{
    public function transform(Tournament $tournament) {

        return [
            'id'    => $tournament->id,
            'name'  => $tournament->name,
            'type'  => '',
            'place' => '',
        ];
    }
}
