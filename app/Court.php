<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Model of courts table.
 *
 * @author Dessaules Loïc
 */
class Court extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    // I added this because when I try to save() Sport value an updated_At "xy" error appears
	// And with this that work
	public $timestamps = false;
	protected $fillable = ['fk_sports', 'name']; // -> We have to define all data we use on our courts table (For use : ->all())

    /**
     * Create a new belongs to relationship instance between Court and Sport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Doran Kayoumi
     */
    public function sport(){
        return $this->belongsTo(Sport::class);
    }
}
