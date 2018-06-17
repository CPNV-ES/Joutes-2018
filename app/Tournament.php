<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * Model of tournaments table.
 *
 * @author Dessaules Loïc
 */

class Tournament extends Model
{

    // I added this because when I try to save() Sport value an updated_At "xy" error appears
    // And with this that work
    public $timestamps = false;
    protected $fillable = array('name', 'start_date', 'end_date', 'start_time', 'event_id','max_teams'); // -> We have to define all data we use on our sport table (For use ->all())
    protected $dates = ['start_date', 'end_date']; //need to user convert format date

    /**
     *
     * Check if the Tournament is in the time range
     *
     * @author Carboni Davide
     *
     * @param Carbon $from
     * @param Carbon $to
     * @return bool
     */
    public function isBetween(Carbon $from, Carbon $to) {
        if (($this->start_date  >= $from) && ($this->end_date <= $to)) return true;
        else return false;
    }

    /**
     *
     * Verify if the tournament take play in all the morning
     *
     * @author Carboni Davide
     * @return bool
     */
    public function takesPlaceInTheMorning() {
        if (($this->end_date->format('H:i:s') <= "13:00")) return true;
        else return false;
    }

    /**
     * Verify if the tournament take play in all the afternoon
     *
     * @author Carboni Davide
     *
     * @return bool
     */
    public function takesPlaceInTheAfternoon() {
        if (($this->start_date->format('H:i:s') >= "13:00")) return true;
        else return false;
    }

    /**
     *
     * Verify if the tournament take play in all the day
     *
     * @author Carboni Davide
     *
     * @return bool
     */
    public function takesPlaceAllTheDay() {
        if (($this->takesPlaceInTheMorning() == false) && ($this->takesPlaceInTheAfternoon() == false)) return true;
        //if ((($this->start_date->format('H:i:s') >= "09:00")) && (($this->end_date->format('H:i:s') <= "18:00")))  return true;
        else return false;
    }

    /**
     * Create a new belongs to relationship instance between Tournament and Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Doran Kayoumi
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Create a new belongs to relationship instance between Tournament and Sport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Doran Kayoumi
     */
    public function sport()
    {
        return $this->belongsTo('App\Sport');
    }

    /**
     * Create a new belongs to many relationship instance between Tournament and Team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Doran Kayoumi
     */
    public function teams()
    {
        return $this->hasMany('App\Team');
    }

    /**
     * Create a new belongs to many relationship instance between Tournament and Pool
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Loïc Dessaules
     */
    public function pools()
    {
        return $this->hasMany('App\Pool');
    }

    /**
     * Get specific pool
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model or void
     *
     * @author Struan Forsyth
     */
    public function pool($id)
    {
        // get tournament pools
        $pools = $this->pools;

        // look for wanted pool
        foreach ($pools as $pool) {
            if ($pool->id == $id) {
                return $pool;
            }
        }
    }

    public function results() {
        $pools = $this->pools;
        $filtered = null;
        if (!empty($pools->last())) {
            $final_stage = $pools->last()->stage;
            $filtered = $pools->filter(function($value, $key) use (&$final_stage) {
                if ($value['stage'] == $final_stage && $value['isFinished'] == 1)
                return $value;
            });
            $pools = null;
        }
        return $filtered;
    }

    /**
     * return the pools of the current stage
     * @param  Boolean withFinishedPool if it is true, the function will return also the finished pools of the current stage
     * @return Collection return a collection of pools
     */
    public function getCurrentStagePools($withFinishedPool = false)
    {
        // depends on $withFinishedPool used
        $pools = ($withFinishedPool) ? $this->pools :  $this->getNotFinishedPools()->sortBy('start_time');

        //if there is at least one pool
        if (!$pools->isEmpty()) {
            $currentStage = $this->getCurrentStage();
            return $pools->filter(function ($value, $key) use ($currentStage) {
                return ($value['stage'] == $currentStage);
            });
        }
        //to not return nothing
        return collect();
    }
    /**
     * get the number of the current stage (the first stage which has not finished pools)
     * @return Collection return a collection of pools
     */
    public function getCurrentStage()
    {
        return $this->pools->where('isFinished', 0)
                            ->sortBy('stage')
                            ->first()
                            ->stage;
    }
    /**
     * return the pools which aren't finished
     * @return Collection return a collection of pools
     */
    public function getNotFinishedPools()
    {
        return $this->pools->filter(function ($value, $key) {
            return !$value['isFinished'];
        });
    }

    /**
     * Get all active games of a tournament
     * @param  integer  $limit - number of games wanted
     * @return collection
     *
     * @author Doran Kayoumi
     */
    public function GetActiveGames($limit)
    {
        $tournament_games = new Collection();

        foreach ($this->pools as $pool) {
            $pool_games = new Collection();

            foreach ($pool->games as $game) {
                if (is_null($game->score_contender1) && is_null($game->score_contender2)) {
                    $pool_games->push($game);
                }
            }

            $pool_games = Game::cleanEmptyContender($pool_games);

            if (count($pool_games) !== 0) {
                $tournament_games = $tournament_games->merge($pool_games);
            }
        }

        return $tournament_games->sortBy('start_time')->take($limit);
    }


    /**
     * Verify if the tornament have all teams required to be full
     *
     * @author Davide Carboni
     */
    public function isComplete(){
        if ($this->teams->count() >= $this->max_teams) return true;
        else return false;
    }


    /**
     * @return bool
     *
     *
     * @author Davide Carboni
     */
    public function haveTeamsEmpty()
    {
        $teams = $this->teams;
        foreach ($teams as $team)
            if ($team->isComplete() == false)
                return true;

        return false;
    }
}
