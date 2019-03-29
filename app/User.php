<?php

namespace App;

<<<<<<< HEAD
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


=======
use Illuminate\Foundation\Auth\User as Authenticatable;

>>>>>>> feature/ShowParticipantsInTeam
class User extends Authenticatable
{   
    protected $fillable = ['username', 'password', 'role'];
    public $timestamps = false;
    protected $hidden = array('password');

<<<<<<< HEAD
    //This function is used for clear the remember_token when you disconnect
    public function getRememberToken(){}
    public function setRememberToken($value){}

=======

    public function getRememberToken(){
    }
    public function setRememberToken($value){
    }
>>>>>>> feature/ShowParticipantsInTeam
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
