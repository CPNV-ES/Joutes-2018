<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Model of contenders table.
 *
 * @author Dessaules Loïc
 */
class Contender extends Model
{
    public $timestamps = false;
    protected $fillable = array('pool_id'); // -> We have to define all data we use on our courts table (For use : ->all())

	/**
     * Create a new belongs to relationship instance between games and contenders (to have the first contender)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Loïc Dessaules
     */
    public function team(){
        return $this->belongsTo('App\Team');
    }

    public function fromPool(){
        return $this->belongsTo('App\Pool', 'pool_from_id');
    }

	public function pool() {
		return $this->belongsTo('App\Pool', 'pool_id');
	}

}
