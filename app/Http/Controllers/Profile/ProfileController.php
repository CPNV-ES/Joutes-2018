<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Tournament;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Team;
use App\Participant;
use App\Event;
use Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /***
     * Display the current profile and verify if a SigIn is required
     * Users must have max two teams! One that play in the morning and one that play in the afternoon
     * Else users can be have only one team that play in the morming and in the afternoon
     *
     * @author Carboni Davide
     */
    public function index(Request $request)
    {
        $participant = Auth::user()->participant()->first();

        // Verify users if have any teams
        if ($participant->isUnsigned($participant->id))
        {
            $teams = $participant->teams;

            // No teams for users
            if ($teams->count() == 0)
                return redirect()->route('profile.create');

            //Verify if the team play in the morning and in the afternoon or only in the morning or only in the afternoon
            if ($teams->count() == 1) {
                $team = $participant->teams->first();
                $tournament = $team->tournament;

                if (($tournament->takesPlaceInTheAfternoon()))
                    return redirect()->route('profile.create', ['toFinish' => 'requiredMorning',"from"=>$request->input("from")]);

                if (($tournament->takesPlaceInTheMorning()))
                    return redirect()->route('profile.create', ['toFinish' => 'requiredAfternoon',"from"=>$request->input("from")]);

                if (($tournament->takesPlaceAllTheDay()))
                    return redirect()->route('profile.show', $participant->id);
            }
        }
        return redirect()->route('profile.show',$participant->id);
    }

    /**
     * Update data
     *
     * @author Carboni Davide
     */
    public function update(Request $request, $id)
    {
        $isNewEquipe = $request->input('switch');
        $tournament = $request->input('tournament');
        $oldTeamID = $request->input('personalTeams');
        $oldTeam = Team::find($oldTeamID);

        // Verify if the user want to SignIn in the Tournament with a new personal team or with a exsist team
        if ($isNewEquipe == null) {
            $participant = Participant::find($id);
            $participant->teams()->detach($oldTeamID);
            if ($oldTeam->isOwner($participant->id)) {
                $participants = $oldTeam->participants;
                if ($participants->count() == 0) {
                    $oldTeam->delete();
                }
                else{
                    $first = $participants->first();
                    $oldTeam->owner_id = $first->user->id;
                    $oldTeam->save();
                }
            }
            $team = Team::where('id',$request->input('teamSelected'))->first();
            $team->participants()->attach([$participant->id => array('isCaptain' => '0' )]); //add the link row in intemrediate table
        }
        else {
            // The team do not exsist so call the TeamController to store the new team
            $participant = Participant::find($id);
            $participant->teams()->detach($oldTeamID);
            if ($oldTeam->isOwner($participant->id)) {
                $participants = $oldTeam->participants;
                if ($participants->count() == 0) {
                    $oldTeam->delete();
                }
                else{
                    $first = $participants->first();
                    $oldTeam->owner_id = $first->user->id;
                    $oldTeam->save();
                }
            }
            $teamName = $request->input('teamNew');
            $newRequest = Request::create('', 'POST', array('name' => $teamName, 'tournament' => $tournament));
            app('App\Http\Controllers\Admin\TeamController')->store($newRequest);
        }

        return redirect()->route('profile.index',['from' => "changeTeam"]);
    }


    /**
     * Prepare the nee form for the first Login In
     *
     * @author Carboni Davide
     */
    public function create(Request $request)
    {
        //Prepare all values for the SignIn login form
        $dropdownListEvent = $this->getDropDownList_Event();
        $toFinish = $request->input("toFinish");

        return view('profile.create')
            ->with('dropdownListEvent', $dropdownListEvent)
            ->with('from', $request->input("from"))
            ->with('toFinish', $toFinish);
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
        $tournament = $request->input('tournament');

        if (($isNewEquipe == null)) {
            $team = Team::where('id',$request->input('teamSelected'))->first();
            $team->participants()->attach([$participant->id => array('isCaptain' => '0' )]); //add the link row in intemrediate table

        }

        if ($isNewEquipe != null) {
            $teamName = $request->input('teamNew');
            $request = Request::create('', 'POST', array('name' => $teamName, 'tournament' => $tournament));
            app('App\Http\Controllers\Admin\TeamController')->store($request);
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
    public function show($id)
    {
        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;
        return view('profile.index')->with('teams', $teams)->with('participant', $participant) ;
    }

    /**
     * Cchange de teams for a user
     *
     * @author Davide Carboni
     *
     * @param Request $request
     * @return $this
     */
    public function edit(Request $request, $id){
        //Prepare all values for the SignIn login form
        $dropdownListPersonalTeams = $this->getDropList_ParticipantTeams($id);
        $toFinish = $request->input("toFinish");

        return view('profile.edit')
            ->with('dropdownListPersonalTeams', $dropdownListPersonalTeams)
            ->with('toFinish', $toFinish)
            ->with('id', $id);
    }

    /**
     *
     * Reset the user registration
     *
     * @author Davide Carboni
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $participant = Participant::find($id);
        $teams = $participant->teams;
        $participant->teams()->detach();
        foreach ($teams as $team){

            if ($team->isOwner($participant->id)) {
                $participants = $team->participants;
                if ($participants->count() == 0) {
                    $team->delete();
                }
                else{
                    $first = $participants->first();
                    $team->owner_id = $first->user->id;
                    $team->save();
                }
            }
        }

        return redirect()->route('profile.index');
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

    private function getDropList_ParticipantTeams($id){
        $participant = Participant::find($id);
        $teams = $participant->teams;
        // Creation of the array will contain the datas of the dropdown teams list
        // This form: array("sport_id 1" => "sport_name 1", "sport_id 2" => "sport_name 2"), ...
        $dropdownListPersonalTeams = array();
        for ($i=0; $i < sizeof($teams); $i++) {
            $dropdownListPersonalTeams[$teams[$i]->id] = $teams[$i]->name;
        }
        return $dropdownListPersonalTeams;
    }

}
