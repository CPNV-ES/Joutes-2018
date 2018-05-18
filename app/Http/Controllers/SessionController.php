<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{

    /**
     * Connect the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function store(Request $request)
    {
        $role = $request->input('role');
        $password = $request->input('password');
        $username = $request->input('username');

        // Check if the user already exsit
        $user = \App\User::where('username', $username)->first();

        // Create a new off-line user if not exsist
        if ($user == null)
            $this->create_Off_Line_Session($username, $role);

        // Login the new off-line user
        Auth::attempt(['username' => $username, 'password' => $password]);

        return "accepted::".url()->previous();
    }

    /**
     * Disconnected the current connected user
     *
     * @param  int  $id (Not important for the disconnected method ... I have to put an id param because I use the laravel resources)
     *
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function destroy($id)
    {
        //Check for off-line user
        if (isset($_ENV['OFFLINE'])) {
            $offline = $_ENV['OFFLINE'];
        }
        else
        {
            $offline = "NO";
        }

        // Check the user type (off-line user or intranet user)
        if ($offline == "YES") {
            //delete off-line user
            $this->delete_offline_user(Auth::user()->username, Auth::user()->role);
            Auth::logout();
            return redirect(route('events.index'));
        }
        else {
            //Check for error
            if ((Auth::user()->username == "ADMIN Tester") || (Auth::user()->username == "PARTICIPANT Tester") || (Auth::user()->username == "WRITER Tester"))
            {
                //delete off-line user
                $this->delete_offline_user(Auth::user()->username, Auth::user()->role);
                Auth::logout();
                return redirect(route('events.index'));
            }
            return redirect()->route('saml_logout');
        }
    }

    /**
     * Create a new off-line user into the db
     *
     * @param $username
     * @param $role
     */
    public function create_Off_Line_Session($username, $role)
    {
        $user = new \App\User;
        $user->username = $username;
        $user->email = $username . "@cpnv.ch";
        $user->password = Hash::make("none");
        $user->last_name = "Tester";
        $user->first_name = str_replace("Tester", '', $username);
        $user->role = $role;
        $user->save();

        // create a new participant for Participant off-line user
        if ($role == "participant")
        {
            $participant = \App\Participant::where('first_name', str_replace("Tester", '', $username))->first();

            //create a new off-line user if do not in db
            if ($participant == null) {
                $participant = new \App\Participant();
                $participant->first_name = str_replace("Tester", '', $username);
                $participant->last_name = "Tester";
                $user->participant()->save($participant);
            }
            else
            {
                $participant->user_id = $user->id;
                $participant->save();
            }
        }
    }

    /**
     * Delete off-line user in db
     *
     * @param $username
     * @param $role
     */

    public function delete_offline_user($username, $role)
    {
        $user = \App\User::where('username', $username)->first();
        if ($role == "participant")
        {
            $participant = \App\Participant::where('first_name', str_replace("Tester", '', $username))->first();
            $participant->delete();
        }
        $user->delete();
    }

}
