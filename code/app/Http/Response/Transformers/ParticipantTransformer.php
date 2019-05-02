<?php
namespace App\Http\Response\Transformers;

use App\Participant;
use League\Fractal\TransformerAbstract;

class ParticipantTransformer extends TransformerAbstract
{
    public function transform(Participant $participant) {

        return [
            'id'        => (int)    $participant->id,
            'firstname' => (string) $participant->first_name,
            'lastname'  => (string) $participant->last_name,
        ];
    }
}
