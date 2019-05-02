<?php
namespace App\Http\Response\Transformers;

use App\Participant;
use League\Fractal\TransformerAbstract;

class SingleParticipantTransformer extends TransformerAbstract
{
    public $defaultIncludes = [
        "tournaments",
        "teams"
    ];

    public function transform(Participant $participant) {

        return [
            'id'        => (int)    $participant->id,
            'firstname' => (string) $participant->first_name,
            'lastname'  => (string) $participant->last_name,
        ];
    }

    public function includeTournaments(Participant $participant) {
        return $this->collection($participant->tournaments(), new ParticipantTournamentTransformer);
    }

    public function includeTeams(Participant $participant) {
        return $this->collection($participant->teams, new ParticipantTeamTransformer);
    }
}
