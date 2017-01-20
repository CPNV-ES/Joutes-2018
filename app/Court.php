<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    // I added this because when I try to save() Sport value an updated_At "xy" error appears
	// And with this that work
	public $timestamps = false;
	protected $fillable = array('fk_sports', 'name'); // -> We have to define all data we use on our courts table (For use : ->all())

    /**
     * Create a new belongs to relationship instance between Court and Sport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Doran Kayoumi
     */
    public function sport(){
        return $this->belongsTo(Sport::class, "fk_sports");
    }
}
