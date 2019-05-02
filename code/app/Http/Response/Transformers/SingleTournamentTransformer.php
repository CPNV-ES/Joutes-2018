<?php
namespace App\Http\Response\Transformers;

use App\Tournament;
use League\Fractal\TransformerAbstract;

class SingleTournamentTransformer extends TransformerAbstract
{
    public $defaultIncludes = [
        "teams",
        "pools"
    ];

    public function transform(Tournament $tournament) {

        return [
            'id'            => (int) $tournament->id,
            'name'          => (string) $tournament->name,
            'sport'         => (string) $tournament->sport->name,
            'type'          => '',
            'place'         => '',
            'winner'        => [],
            'second'        => [],
            'third'         => [],
        ];
    }

    public function includeTeams(Tournament $tournament) {
        return $this->collection($tournament->teams, new TournamentTeamTransformer);
    }
    public function includePools(Tournament $tournament)
    {
        return $this->collection($tournament->pools, new TournamentPoolTransformer);
    }
}
