<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends Model implements Authenticatable
{   
    protected $fillable = ['username', 'password', 'role'];
    public $timestamps = false;
    protected $hidden = array('password');

    /* Implemented methods from Authenticatable */
    public function getAuthIdentifierName(){
        return $this->username;
    }
    public function getAuthIdentifier(){
        return $this->id;
    }
    public function getAuthPassword(){
        return $this->password;
    }
    public function getRememberToken(){
    }
    public function setRememberToken($value){
    }
    public function getRememberTokenName(){
    }
    public function getRole(){
        return $this->role;
    }

    public function participant()
    {
        return $this->hasOne('App\Participant');
    }

    public function teams()
    {
        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;
        return $teams;
        //return $this->hasManyThrough('App\Team', 'App\Participant', 'team_id','user_id' );
    }
    
    // Returns whether the user is locally authenticated or remotely (SAML)
    public function isLocal()
    {
        return !empty($this->password);
    }

}
