<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdministrationController extends Controller
{
    /**
      * Display a listing of courts.
      *
      * @return \Illuminate\Http\Response
      *
      * @author Butticaz Yvann
      */
 
    public function index(){
        return view('administration.index');
    }

 }
