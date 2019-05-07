<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    //
    public function participant()
    {
        return $this->belongsTo('App\Participant', 'participant_id');
    }
}
