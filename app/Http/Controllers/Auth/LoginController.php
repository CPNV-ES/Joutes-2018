<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/events';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /* I define that laravel have to use the "username" field for login in place of "email" by default */
    public function username(){
        return 'username';
    }

    public function authenticate($request){
        if (Auth::attempt(['username' => $request->input("username"), 'username' => $request->input("password")])) {
            // Authentication passed...
            //return redirect()->intended('dashboard');
            echo "OOOOKKKKK";
        }
    }

}
