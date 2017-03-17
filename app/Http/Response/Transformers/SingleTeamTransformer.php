<?php
namespace App\Http\Response\Transformers;

use App\Team;
use App\Tournament;
use League\Fractal\TransformerAbstract;

class SingleTeamTransformer extends TransformerAbstract
{
    public $defaultIncludes = [
        "members",
        'tournament',
    ];

    public function transform(Team $team) {

        return [
            'id'         => (int) $team->id,
            'name'       => (string) $team->name,
            'status'     => (string) '',
            'sport'      => $team->sport->name,
            'matches'    => [],
        ];
    }

    public function includeMembers(Team $team) {
        return $this->collection($team->participants, new TeamMemberTransformer);
    }

    public function includeTournament(Team $team) {
        return $this->item($team->tournament, new TeamTournamentTransformer);
    }
}
