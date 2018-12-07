<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    //

    public function index(Request $request){
        try{
            $user = \Auth::user();
        }
        catch(\Exception $e){
            $user = null;
        }
        try{
            $teams = \Auth::user()->participant()->first()->teams;
        }
        catch(\Exception $e){
            $teams = null;
        }
        return [
            'user' => $user,
            'teams' => $teams,
        ];
    }
}
