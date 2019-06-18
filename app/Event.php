<?php

namespace App;

use App\Team;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Event extends Model
{

    public $timestamps = false;
    protected $fillable = array('name', 'img'); 

    /**
     * Get event tournaments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @author Doran Kayoumi
     */
    public function tournaments() {
        return $this->hasMany('App\Tournament')->orderBy('sport_id'); // XCL: temporary fix on order, just to keep admin and injured fake tournaments at the end of the list
    }

    /**
     * Get specific tournament
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model or void
     *
     * @author Doran Kayoumi
     */
    public function tournament($id) {

        // get event tournaments
        $tournaments = $this->tournaments()->get();

        // look for wanted tournament
        foreach ($tournaments as $tournament) {
            if ($tournament->id == $id) {
                return $tournament;
            }
        }
    }

    /**
     * Get event teams
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @author Doran Kayoumi
     */
    public function teams() {
        return $this->hasManyThrough('App\Team', 'App\Tournament');
    }

    /**
     * Get specific team
     *
     * @param  int  $id
     * @return boolean
     *
     * @author Doran Kayoumi
     */
    public function team($id) {

        // all event teams
        $teams = $this->teams;

        // loop through teams
        foreach ($teams as $team) {
            if ($team->id == $id)
                return $team;
        }
    }

    public function participants() {
        // get event teams
        $teams = $this->teams;
        // create empty array for participants
        $participants = [];

        foreach ($teams as $team) {
            foreach ($team->participants as $participant) {
                $participants[] = ($participant);
            }
        }

        return collect($participants)->unique("id");
    }

    public function participant($id) {

        $participants = $this->participants();

        // loop through teams
        foreach ($participants as $participant) {
            if ($participant->id == $id)
                return $participant;
        }
    }
}
