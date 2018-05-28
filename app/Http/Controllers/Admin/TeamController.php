<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Team;
use App\Participant;
use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function index()
    {
        $teams = Team::all();
        return view('team.index', array(
            "teams" => $teams,
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('team.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* LARAVEL VALIDATION */
        // create the validation rules
        $rules = array(
            'name' => 'required|min:3|max:35|unique:teams,name'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return view('team.create')->withErrors($validator->errors());
        } else {
            $team = new Team();
            $team->name = $request->input('name');
            $team->tournament_id = null;
            $team-> validation = 0;
            $team->owner_id = Auth::user()->id;
            $team->save();

            if (Auth::user()->role == "participant") {
                $participant = Auth::user()->participant()->first();
                $team->participants()->attach([$participant->id => array('isCaptain' => '0' )]); //add the link row in intemrediate table
            }

            if (Auth::user()->role == "administrator") {
                $team->save();
            }

            return redirect()->route('profile.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function show($id)
    {
        $team = Team::find($id); 
        $error = $infos = null;

        $pepoleNoTeam = Participant::doesntHave('teams')->get();

        // Creation of the array will contain the datas of the dropdown list
        // This form: array("sport1" => "sport1", "sport2" => "sport2"), ...
        $dropdownList = array();
        for ($i=0; $i < sizeof($pepoleNoTeam); $i++) {
            $dropdownList[$pepoleNoTeam[$i]->id] = $pepoleNoTeam[$i]->last_name . " " . $pepoleNoTeam[$i]->first_name;
        }

        if(empty($dropdownList))
            $error = "Aucun membre ne peut être ajouté car ils font tous déjà partis d'une team !";

        if(Cookie::get('infos') != null){
            $infos = Cookie::get('infos');
            Cookie::queue(Cookie::forget('infos')); //delete cookie
        }

        return view('team.show', array(
            "team"         => $team,
            "dropdownList" => $dropdownList,
            "error"        => $error,
            "infos"        => $infos,
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function edit($id)
    {
        $team = Team::find($id);
        return view('team.edit', array(
            "team" => $team,
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * 
     * @author Dessauges Antoine
     */
    public function update(Request $request, $id)
    {

        $team = Team::find($id);
        $error = null;

        // Test: must be begin with 3caracter min. (all sports have minimum 3 caracters)
        $pattern = '/^[a-zA-Z]{3}/';

        // Check if name is empty OR has minimum 3 caracter at the beginning
        if(empty($request->input('name')) || !preg_match($pattern, $request->input('name'))){
            $error = 'Nom de team invalide, 3 caractères minimum';
        }
        // Check if the name already exists
        else if(Team::where('name', '=', $request->input('name'))->exists()){
            $error = '"'.$request->input('name').'"'.' existe déjà';
        }

        if(empty($error)){
            $team->update($request->all());
            return redirect()->route('teams.index');
        }else{
            return view('team.edit', array(
                "error" => $error,
                "team" => $team,
            ));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function destroy($id)
    {
        $team = Team::find($id);
        $team->delete();
        return redirect()->route('teams.index');
    }


}
