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
        if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
            return "accepted::".url()->previous();
        } else {
            $errorLogin = "Nom d'utilisateur ou mot de passe incorrect";
            return "refused::".$errorLogin;
        }
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
        if (\Auth::user()->isLocal()) {
            Auth::logout();
            return redirect(route('events.index'));
        } else {
            return redirect()->route('saml_logout');
        }
    }

}
