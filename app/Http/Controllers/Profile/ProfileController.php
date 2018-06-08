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
        $dropdownListEvent = $this->getDropDownList_Event();

        $participant = Auth::user()->participant()->first();
        $teams = $participant->teams;

        return view('profile.create')
            ->with('teams', $teams)
            ->with('participant', $participant)
            ->with('dropdownListEvent', $dropdownListEvent);
    }

    public function store(Request $request)
    {
        /* CUSTOM SPECIFIC VALIDATION */
        $customError = null;

        /* LARAVEL VALIDATION */
        // create the validation rules

        $caseSelected = $request->input('switch');

       if ($caseSelected == null)
       {
           $rules = array(
               'event' => 'required',
               'tournament' => 'required',
               'teamSelected' => 'required'
           );
       }
       else
       {
           $rules = array(
               'teamNew' => 'required|min:1|max:20|unique:teams,name',
               'event' => 'required',
               'tournament' => 'required'
           );
       }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails() || !empty($customError)) {
            $dropdownListEvent = $this->getDropDownList_Event();

            $participant = Auth::user()->participant()->first();
            $teams = $participant->teams;

            return view('profile.create')
                ->with('teams', $teams)
                ->with('participant', $participant)
                ->with('dropdownListEvent', $dropdownListEvent)
                ->withErrors($validator->errors());
        } else {
            /*
            $court = new Court;
            $court->name = $request->input('name');
            $court->acronym = $request->input('acronym');
            $court->sport_id = $request->input('sport');
            $court->save();

            return redirect()->route('courts.index');
            */
        }

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
