<?php
namespace App\Http\Response\Transformers;

use App\Pool;
use League\Fractal\TransformerAbstract;

class TournamentPoolTransformer extends TransformerAbstract
{
    public function transform(Pool $pool) {
        return [
            'id'            => $pool->id,
            'name'          => $pool->poolName,
            'stage'         => $pool->stage,
            'isFinished'    => $pool->isFinished
        ];

    }
}
