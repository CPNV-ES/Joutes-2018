<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = true;
    //
    public function team(){
        return $this->belongsTo('App\Team', 'team_id');
    }
}
