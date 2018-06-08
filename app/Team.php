<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','isCaptain'];

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
}
