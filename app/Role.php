<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['slug', 'name'];
    public $timestamps = false;


    public function getSlug(){
        return $this->slug;
    }

    
    public function getName(){
        return $this->name;
    }

    public function users(){
        return $this->hasMany('App\User', 'roles_id');
    }

    

}
