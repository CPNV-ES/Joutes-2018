<?php
namespace App\Http\Response\Transformers;

use App\Participant;
use League\Fractal\TransformerAbstract;

class ParticipantTournamentTransformer extends TransformerAbstract
{
    public function transform($tournament) {

        return [
            'id'    => (int)    $tournament->id,
            'name'  => (string) $tournament->name,
            'sport' => (string) $tournament->sport->name,
            'place' => '',
        ];
    }
}
