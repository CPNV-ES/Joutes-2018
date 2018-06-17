<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Team;
use App\Participant;
use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Event;

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
        //Prepare all values for the SignIn login form
        $dropdownListEvent = $this->getDropDownList_Event();
        $dropdownListEventTournaments = array();
        $tournamentsOptions = ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'tournament', 'disabled' => 'disabled'];
        $teamNewOptions = ['class' => 'form-control', 'disabled' => 'disabled', 'id' => 'name'];

        return view('team.create')
            ->with('dropdownListEvent', $dropdownListEvent)
            ->with('dropdownListEventTournaments', $dropdownListEventTournaments)
            ->with('tournamentsOptions', $tournamentsOptions)
            ->with('teamNewOptions', $teamNewOptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = $request->input('event');

        if ($request->input('from') == 'team') { // check if the create method is called from profile form or form the create form
            /* LARAVEL VALIDATION */
            // create the validation rules
            $rules = array(
                'name' => 'unique:teams,name'
            );
            $validator = Validator::make(array('name' => $request->input('name')), $rules);
            if ($validator->fails()) {
                // The new created team exsist so prepare the form with the old selections for a new input
                $dropdownListEvent = $this->getDropDownList_Event();
                $dropdownListEventTournaments = $this->getDropDownList_EventTournaments($event);
                $tournamentsOptions = ['class' => 'form-control allSameStyle', 'id' => 'tournament'];
                $teamNewOptions = ['class' => 'form-control', 'id' => 'name'];
                $error = "Le nom de l'équipe " . $request->input("name") . " viens d'etre créer par un'autre utilisateur ";
                return view('team.create')
                    ->with('error', $error)
                    ->with('dropdownListEvent', $dropdownListEvent)
                    ->with('dropdownListEventTournaments', $dropdownListEventTournaments)
                    ->with('tournamentsOptions', $tournamentsOptions)
                    ->with('teamNewOptions', $teamNewOptions);
            }
        }

        $team = new Team();
        $team->name = $request->input('name');
        $team->tournament_id = $request->input('tournament');
        $team->validation = 0;
        $team->owner_id = Auth::user()->id;
        if (Auth::user()->role == "administrator")
            $team->validation = 1;
        else
            $team->validation = 0;

        $team->save();

        if (Auth::user()->role == "participant") {
            $participant = Auth::user()->participant()->first();
            $team->participants()->attach([$participant->id => array('isCaptain' => '0' )]); //add the link row in intemrediate table
        }
        /*
        if (Auth::user()->role == "administrator") {
            $team->save();
        }
        */
        return redirect()->route('profile.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function show(Request $request, $id)
    {
        // return the id for a username using ajax
        if ($request->ajax())
        {
            if ($request->input("isFull") == "isFull"){
                $team = Team::find($id);
                if ($team->isComplete())return 1;
                else return 0;
            }

            if ($request->input("timeZone") == "timeZone"){
                $team = Team::find($id);
                if ($team->tournament->takesPlaceInTheMorning()) return (["inTheMorning"]);
                if ($team->tournament->takesPlaceInTheAfternoon()) return (["inTheAfternoon"]);
                if ($team->tournament->takesPlaceAllTheDay()) return (["inTheDay"]);
            }

            if ($request->input("teamExisistName") == "teamExisistName"){
                $team = Team::where('name', $id)->first();
                if ($team == null) return 0;
                else return 1;
            }
        }

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

    /**
     * Create the array with all events
     *
     * @return array
     *
     * @author Carboni Davide
     */
    private function getDropDownList_Event(){
        $events =Event::all();
        // Creation of the array will contain the datas of the dropdown event list
        // This form: array("sport_id 1" => "sport_name 1", "sport_id 2" => "sport_name 2"), ...
        $dropdownListEvent = array();
        for ($i=0; $i < sizeof($events); $i++) {
            $dropdownListEvent[$events[$i]->id] = $events[$i]->name;
        }
        return $dropdownListEvent;
    }


    /**
     * Prepare array data for all tournament's event
     *
     * @param $id
     * @return array
     *
     * @author Carboni Davide
     */
    private function getDropDownList_EventTournaments($id){
        $event = Event::findOrFail($id);
        $tournaments = $event->tournaments;
        // Creation of the array will contain the datas of the dropdown tournament list
        // This form: array("sport_id 1" => "sport_name 1", "sport_id 2" => "sport_name 2"), ...
        $dropdownListEventTournaments = array();
        for ($i=0; $i < sizeof($tournaments); $i++) {
            $dropdownListEventTournaments[$tournaments[$i]->id] = $tournaments[$i]->name;
        }
        return $dropdownListEventTournaments;
    }


    /**
     * Prepare array data width all team's tournament
     *
     * @param $id
     * @return array
     *
     * @author Carboni Davide
     */
    private function getDropDownList_TournamentTeams($id){
        $tournament = Tournament::findOrFail($id);
        $teams = $tournament->teams;
        // Creation of the array will contain the datas of the dropdown teams list
        // This form: array("sport_id 1" => "sport_name 1", "sport_id 2" => "sport_name 2"), ...
        $dropdownListTournamentTeams = array();
        for ($i=0; $i < sizeof($teams); $i++) {
            $dropdownListTournamentTeams[$teams[$i]->id] = $teams[$i]->name;
        }
        return $dropdownListTournamentTeams;
    }
}
