<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    // I added this because when I try to save() Sport value an updated_At "xy" error appears
    // And with this that work
    public $timestamps = false;
    protected $fillable = array('id', 'first_name', 'last_name'); // -> We have to define all data we use on our courts table (For use : ->all())

    public function teams()
    {
        return $this->belongsToMany('App\Team')->withPivot('isCaptain');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tournaments() {
        // get event teams
        $teams = $this->teams;
        // create empty array for participants
        $tournaments = [];

        foreach ($teams as $team) {
            $tournaments[] = $team->tournament;
        }

        return collect($tournaments)->unique("id");
    }
}
