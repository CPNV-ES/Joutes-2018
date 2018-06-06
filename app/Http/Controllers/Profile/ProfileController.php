<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Tournament;
use Illuminate\Http\Request;
use App\Team;
use App\Participant;
use App\Event;
use Auth;

class ProfileController extends Controller
{
    /*
     * Display the current profile
     */

    public function index()
    {
        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;
        if ($participant->isUnsigned($participant->id))
            return redirect()->route('profile.create');

        return view('profile.index')->with('teams', $teams)->with('participant', $participant) ;
    }

    public function update()
    {
        return view('profile.index');
    }

    public function create()
    {
        $dropdownListTeams = $this->getDropDownList_Teams();
        $dropdownListEvent = $this->getDropDownList_Event();
        $dropdownListTournements = $this->getDropDownList_Tornements();

        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;

        return view('profile.create')
            ->with('teams', $teams)
            ->with('participant', $participant)
            ->with('dropdownListTeams', $dropdownListTeams)
            ->with('dropdownListTournements', $dropdownListTournements)
            ->with('dropdownListEvent', $dropdownListEvent);
    }

    public function store(Request $request)
    {

    }

    public function show(Request $request, $id)
    {
        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;
        return view('team.index', array(
            "teams" => $teams,
        ));

    }

    public function destroy()
    {

    }

    private function getDropDownList_Tornements(){
        $tornementes = Tournament::all();
        // Creation of the array will contain the datas of the dropdown list
        // This form: array("sport_id 1" => "sport_name 1", "sport_id 2" => "sport_name 2"), ...
        $dropdownListTournements = array();
        for ($i=0; $i < sizeof($tornementes); $i++) {
            $dropdownListTournements[$tornementes[$i]->id] = $tornementes[$i]->name;
        }
        return $dropdownListTournements;
    }

    private function getDropDownList_Teams(){
        $teams = Team::all();
        // Creation of the array will contain the datas of the dropdown list
        // This form: array("sport_id 1" => "sport_name 1", "sport_id 2" => "sport_name 2"), ...
        $dropdownListTeams = array();
        for ($i=0; $i < sizeof($teams); $i++) {
            $dropdownListTeams[$teams[$i]->id] = $teams[$i]->name;
        }
        return $dropdownListTeams;
    }

    private function getDropDownList_Event(){
        $events =Event::all();
        // Creation of the array will contain the datas of the dropdown list
        // This form: array("sport_id 1" => "sport_name 1", "sport_id 2" => "sport_name 2"), ...
        $dropdownListEvent = array();
        for ($i=0; $i < sizeof($events); $i++) {
            $dropdownListEvent[$events[$i]->id] = $events[$i]->name;
        }
        return $dropdownListEvent;
    }

}
