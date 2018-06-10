<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Tournament;
use Illuminate\Http\Request;
use App\Team;
use App\Participant;
use App\Event;
use Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /***
     * Display the current profile
     *
     * @author Carboni Davide
     */
    public function index()
    {
        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;
        // Verify users if have any teams
        if ($participant->isUnsigned($participant->id))
            return redirect()->route('profile.create');

        return view('profile.index')->with('teams', $teams)->with('participant', $participant) ;
    }

    /**
     * Update data
     *
     * @author Carboni Davide
     */
    public function update()
    {
        return view('profile.index');
    }


    /**
     * Prepare the nee form for the first Login In
     *
     * @author Carboni Davide
     */
    public function create()
    {
        //Prepare all values for the SignIn login form
        $dropdownListEvent = $this->getDropDownList_Event();
        $dropdownListEventTournaments = array();
        $dropdownListTournamentTeams = array();
        $tournamentsOptions = ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'tournament', 'disabled' => 'disabled'];
        $teamsOptions = ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'teamSelected', 'disabled' => 'disabled'];
        $checkBoxOptions = ['class' => 'switch', "id"=>'switch', 'disabled' => 'disabled'];
        $checkBoxActive = false;
        $teamNewOptions = ['class' => 'form-control', 'disabled' => 'disabled', 'id' => 'teamNew'];

        return view('profile.create')
            ->with('dropdownListEvent', $dropdownListEvent)
            ->with('dropdownListEventTournaments', $dropdownListEventTournaments)
            ->with('dropdownListTournamentTeams', $dropdownListTournamentTeams)
            ->with('tournamentsOptions', $tournamentsOptions)
            ->with('teamsOptions', $teamsOptions)
            ->with('checkBoxOptions', $checkBoxOptions)
            ->with('checkBoxActive', $checkBoxActive)
            ->with('teamNewOptions', $teamNewOptions);
    }

    /**
     * Store and verify the new participant for the first time
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     *
     * @author Carboni Davide
     */
    public function store(Request $request)
    {
        $isNewEquipe = $request->input('switch');
        $participant = Auth::user()->participant()->first();
        $event = $request->input('event');
        $tournament = $request->input('tournament');

        // Verify if the user want to SignIn in the Tournament with a new personal team or with a exsist team
        if ($isNewEquipe == null) {
            $team = Team::where('id',$request->input('teamSelected'))->first();
            $team->participants()->attach([$participant->id => array('isCaptain' => '0' )]); //add the link row in intemrediate table
        }
        else {
            $rules = array('teamNew' => 'unique:teams,name');
            // Verify if the new created team already exsist in the DB
            $validator = Validator::make(array('teamNew'=>$request->input('teamNew')), $rules);
            if ($validator->fails()) {
                // The new created team exsist so prepare the form with the old selections for a new input
                $dropdownListEvent = $this->getDropDownList_Event();
                $dropdownListEventTournaments = $this->getDropDownList_EventTournaments($event);
                $dropdownListTournamentTeams = $this->getDropDownList_TournamentTeams($tournament);
                $tournamentsOptions = ['class' => 'form-control allSameStyle', 'id' => 'tournament'];
                $teamsOptions = ['class' => 'form-control allSameStyle', 'id' => 'teamSelected', 'disabled' => 'disabled'];
                $checkBoxOptions = ['class' => 'switch', "id"=>'switch'];
                $checkBoxActive = true;
                $teamNewOptions = ['class' => 'form-control', 'id' => 'teamNew'];

                $error = "Le nom de l'équipe " . $request->input("teamNew") . " viens d'etre créer par un'autre utilisateur ";
                return view('profile.create')
                    ->with('error',$error)
                    ->with('dropdownListEvent', $dropdownListEvent)
                    ->with('dropdownListEventTournaments', $dropdownListEventTournaments)
                    ->with('dropdownListTournamentTeams', $dropdownListTournamentTeams)
                    ->with('tournamentsOptions', $tournamentsOptions)
                    ->with('teamsOptions', $teamsOptions)
                    ->with('checkBoxOptions', $checkBoxOptions)
                    ->with('checkBoxActive', $checkBoxActive)
                    ->with('teamNewOptions', $teamNewOptions);
            }
            else {
                // The team do not exsist so call the TeamController to store the new team
                $teamName = $request->input('teamNew');
                $request = Request::create('', 'POST', array('name' => $teamName, 'tournament' => $tournament));
                app('App\Http\Controllers\Admin\TeamController')->store($request);
            }
        }

        return redirect()->route('profile.index');
    }

    /**
     * Show the participant's teams
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     *
     *
     * @author Carboni Davide
     */
    public function show(Request $request, $id)
    {
        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;
        //$request = Request::create('', 'POST', array('participant' => $participant->id));
        return redirect()->route('teams.index',['split' => 1]);
    }

    public function destroy()
    {
        //
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
