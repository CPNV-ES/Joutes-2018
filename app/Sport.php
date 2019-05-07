<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Model of sports table.
 *
 * @author Dessaules Loïc
 */

class Sport extends Model
{
	// I added this because when I try to save() Sport value an updated_At "xy" error appears
	// And with this that work
	public $timestamps = false;
	protected $fillable = array('name', 'description','max_participant','min_participant'); // -> We have to define all data we use on our sport table (For use ->all())

	/**
	 * Create a new has many relationship instance between Court and Sport
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 * @author Doran Kayoumi
	 */
    public function courts() {
        return $this->hasMany(Court::class);
    }

	public function tournaments() {
		return $this->hasMany('App\Tournament');
	}
}
