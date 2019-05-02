<?php
namespace App\Http\Response\Transformers;

use App\Team;
use League\Fractal\TransformerAbstract;

class TeamTransformer extends TransformerAbstract
{
    public function transform(Team $team) {

        return [
            'id'     => (int) $team->id,
            'name'   => (string) $team->name,
            'sport'  => $team->sport->name,
        ];
    }
}
