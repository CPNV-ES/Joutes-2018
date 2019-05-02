<?php
namespace App\Http\Response\Transformers;

use App\Pool;
use League\Fractal\TransformerAbstract;

class SinglePoolTransformer extends TransformerAbstract
{
    public $defaultIncludes = [
        'matches'
    ];

    public function transform(Pool $pool) {

        return [
            'id'            => $pool->id,
            'idTournament'  => $pool->tournament_id,
            'startTime'     => $pool->start_time,
            'endTime'       => $pool->end_time,
            'name'          => $pool->poolName,
            'idMode'        => $pool->mode_id,
            'stage'         => $pool->stage,
            'idGameType'    => $pool->game_type_id,
            'size'          => $pool->poolSize,
            'isFinished'    => $pool->isFinished,
            'ranking'       => $pool->rankings()
        ];
    }

    public function includeMatches(Pool $pool) {
        return $this->collection($pool->games, new PoolMatchesTransformer);
    }
}
