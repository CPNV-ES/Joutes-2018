<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Participant;

class Team extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','isCaptain','owner_id','validation'];

    /**
     * Get team participants
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Doran Kayoumi
     */
    public function participants()
    {
        return $this->belongsToMany('App\Participant')->withPivot('isCaptain');
    }

    /**
     * Get team tournament
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Doran Kayoumi
     */
    public function tournament()
    {
        return $this->belongsTo('App\Tournament');
    }

    /**
     * Get sport of a team
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @author Doran Kayoumi
     */
    public function sport()
    {
        return $this->tournament->sport();
    }

    public function games_contender_1()
    {
        return $this->hasManyThrough('App\Game', 'App\Contender', 'team_id', 'contender1_id');
    }

    public function games_contender_2()
    {
        return $this->hasManyThrough('App\Game', 'App\Contender', 'team_id', 'contender2_id');
    }

    public function games()
    {
        $collection = $this->games_contender_1->merge($this->games_contender_2);
        return $collection->sortBy('start_time');
    }

    /**
     *
     * Get team that match the name
     *
     * @param $query
     * @param $name
     * @return mixed
     *
     * @author Davide Carboni
     */

    public function scopeSearch($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Verify if the teams have all participants required to be full
     *
     * @author Davide Carboni
     */
    public function isComplete(){
        if ($this->participants()->count() >= $this->sport->max_participant) return true;
        else return false;
    }

    /**
     *
     * Check if the participant is the owner of the team
     *
     * @author Davide Carboni
     *
     * @param $id
     * @return bool
     */

    public function isOwner($id)
    {
        $participant = Participant::find($id);
        $user_id = $participant->user->id;
        if ($this->owner_id == $user_id) return true;
        else return false;
    }
}
