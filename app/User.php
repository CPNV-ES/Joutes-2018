<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['username', 'password', 'role'];
    public $timestamps = false;
    protected $hidden = array('password');


    public function getRememberToken(){
    }
    public function setRememberToken($value){
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
