<?php
/*
15.03.2017
TODO : 
  - Create - update views, we can only choose teams who don't are on any tournament. (and the current tournament's team)
*/
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tournament;
use App\Sport;
use App\Team;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TournamentController extends Controller
{
    /**
     * Show the form for creating a new tournament.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function create(Request $request)
    {
        $event = Event::find($request->input("event"));
        // Get all sports with one or more court linked
        $dropdownListSportsWithCourt = $this->getDropDownListSportsWithCourt();
        // Get all sports who have no court linked
        $dropdownListSportsWithNoCourt = $this->getDropDownListSportsWithNoCourt();
        // Get only the teams who don't participate to any tournament AND the teams who are participating to the tournament
        $dropdownListTeams = $this->getDropDownListTeams(null);
        return view('tournament.create')->with('dropdownListSportsWithCourt', $dropdownListSportsWithCourt)
                                        ->with('dropdownListSportsWithNoCourt', $dropdownListSportsWithNoCourt)
                                        ->with('dropdownListTeams', $dropdownListTeams)
                                        ->with('event', $event);
    }

    /**
     * Store a newly created tournament in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function store(Request $request)
    {
        /* CUSTOM SPECIFIC VALIDATION */
        $customErrors = array();

        $patternTime =  '/^([01]\d|2[0-3]):?([0-5]\d)$/';
        $patternDate = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
        // The time must be HH:MM
        if(!preg_match($patternTime, $request->input('startTime'))){
            $customErrors[] =  "Le champ Heure de début doit être sous la forme : hh:mm.";
        }
        // The date must be jj:mm:YYYY (laravel cast in YYYY-jj-mm)
        if(!preg_match($patternDate, $request->input('startDate'))){
            $customErrors[] = "Le champ Date de début doit être sous la forme : jj.mm.YYYY.";
        }

        // Check if the name of the new tournaments and his sport already exists in the DB
        // example : Tournament 1 -> Football // I cannot create a new Tournament 1 -> Football // But I can create a Tournament 1 -> Tennis
        if(Tournament::where('name',$request->input('name'))
                     ->where('sport_id', $request->input('sport')) // second where = and
                     ->count() >= 1){
            $sportName = Sport::find($request->input('sport'))->name;
            $customErrors[] = "Le sport \"".$sportName."\" est déjà lié au tournoi \"".$request->input('name')."\"";
        }

        /* LARAVEL VALIDATION */
        // create the validation rules
        $rules = array(
            'name' => 'required|min:3|max:40',
            'sport' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails() || !empty($customErrors)) {
            // Get all sports with one or more court linked
        $dropdownListSportsWithCourt = $this->getDropDownListSportsWithCourt();
        // Get all sports who have no court linked
        $dropdownListSportsWithNoCourt = $this->getDropDownListSportsWithNoCourt();
        // Get only the teams who don't participate to any tournament AND the teams who are participating to the tournament
        $dropdownListTeams = $this->getDropDownListTeams(null);
            return view('tournament.create')->with('dropdownListSportsWithCourt', $dropdownListSportsWithCourt)
                                            ->with('dropdownListSportsWithNoCourt', $dropdownListSportsWithNoCourt)
                                            ->with('dropdownListTeams', $dropdownListTeams)
                                            ->withErrors($validator->errors())
                                            ->with('customErrors', $customErrors);
        } else {
            //Save the tournament
            $tournament = new Tournament;
            $tournament->name = $request->input('name');
            $tournament->start_date = $request->input('startDate')." ". $request->input('startTime').":00";
            $tournament->event_id = $request->input('eventId'); // !!!!!!!!!!!!!!!!! TO CHANGE !!!!!!!!!!!!!!!!
            $tournament->img = 'changeLater';
            $tournament->sport_id = $request->input('sport');
            $tournament->save();

            return redirect()->route('tournaments.index');
        }
    }

    /**
     * Show the form for editing the specified tournament.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function edit($id){ 
      $tournament = Tournament::find($id);

      // Get all sports with one or more court linked
      $dropdownListSportsWithCourt = $this->getDropDownListSportsWithCourt();
      // Get all sports who have no court linked
      $dropdownListSportsWithNoCourt = $this->getDropDownListSportsWithNoCourt();

      // Get only the teams who don't participate to any tournament AND the teams who are participating to the tournament
      $dropdownListTeams = $this->getDropDownListTeams($tournament);


      // Create array with ID of each teams who participating because I need this to display participating teams in the "placeholder" of the select
      $teamsAreParticipating = $tournament->teams;
      if(count($teamsAreParticipating) > 0){
          foreach ($teamsAreParticipating as $team) {
              $teamsAreParticipatingId[] = $team->id;
          }
      }else{
        $teamsAreParticipatingId = null;
      }

      // normal case, there is a sport linked
      if(isset($tournament->sport)){
          $sport = $tournament->sport; // get the sport linked
      }else{
          // Sport has been deleted
          $sport = null; 
      }
      return view('tournament.edit')->with('tournament', $tournament)
                                    ->with('dropdownListSportsWithCourt', $dropdownListSportsWithCourt)
                                    ->with('dropdownListSportsWithNoCourt', $dropdownListSportsWithNoCourt)
                                    ->with('sport', $sport)
                                    ->with('dropdownListTeams', $dropdownListTeams)
                                    ->with('teamsAreParticipatingId', $teamsAreParticipatingId);
    }

    /**
     * Update the specified tournament in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function update(Request $request, $id)
    {
        /* CUSTOM SPECIFIC VALIDATION */
        $customErrors = array();

        $patternTime =  '/^([01]\d|2[0-3]):?([0-5]\d)$/';
        $patternDate = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
        // The time must be HH:MM
        if(!preg_match($patternTime, $request->input('startTime'))){
            $customErrors[] =  "Le champ Heure de début doit être sous la forme : hh:mm.";
        }
        // The date must be jj:mm:YYYY (laravel cast in YYYY-jj-mm)
        if(!preg_match($patternDate, $request->input('startDate'))){
            $customErrors[] = "Le champ Date de début doit être sous la forme : jj.mm.YYYY.";
        }

        // Check if the name of the new tournaments and his sport already exists in the DB
        // example : Tournament 1 -> Football // I cannot create a new Tournament 1 -> Football // But I can create a Tournament 1 -> Tennis
        if(Tournament::where('name',$request->input('name'))
                     ->where('sport_id', $request->input('sport')) // second where = and
                     ->count() >= 1){
          // Check if we are updating the current tournament who have not other same tournament as itself
          $tournaments = Tournament::where('name',$request->input('name'))->where('sport_id', $request->input('sport'))->get();
          $modifyCurrentTournament = false;
          foreach ($tournaments as $tournament) {
            if($tournament->id == $id){
              $modifyCurrentTournament = true;
            }
          }
          if(!$modifyCurrentTournament){
            $sportName = Sport::find($request->input('sport'))->name;
            $customErrors[] = "Le sport \"".$sportName."\" est déjà lié au tournoi \"".$request->input('name')."\"";
          }
        }


        /* LARAVEL VALIDATION */
        // create the validation rules
        $rules = array(
            'name' => 'required|min:3|max:40',
            'sport' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        /* errors detected */
        if ($validator->fails() || !empty($customErrors)) {
            $tournament = Tournament::find($id);
            if(isset($tournament->sport)){
                $sport = $tournament->sport;
            }else{
                $sport = null;
            }
            
            $dropdownListSports = $this->getDropDownListSports();
            $dropdownListTeams = $this->getDropDownListTeams();
            return view('tournament.edit')->with('dropdownListSports', $dropdownListSports)
                                          ->with('dropdownListTeams', $dropdownListTeams)
                                          ->with('tournament', $tournament)
                                          ->with('sport', $sport)
                                          ->with('customErrors', $customErrors)
                                          ->withErrors($validator->errors());
        } else {
          
            //Save the tournament
            $tournament = Tournament::find($id);
            $tournament->name = $request->input('name');
            $tournament->start_date = $request->input('startDate')." ". $request->input('startTime').":00";
            $tournament->sport_id = $request->input('sport');
            $tournament->update();

            // Dissociate all teams linked to the tournament
            foreach ($tournament->teams as $team) {
              $team->tournament()->dissociate();
              $team->update();
            }
            // Accociate new teams linked
            if(!empty($request->input('teams'))){
              foreach ($request->input('teams') as $teamId) {
                $team = Team::find($teamId);
                $team->tournament()->associate($tournament);
                $team->update();
              }
            }

            return redirect()->route('tournaments.index');
        }
    }

    /**
     * Remove the specified tournament from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function destroy($id)
    {
        $tournament = Tournament::findOrFail($id);
        $tournament->delete();
        return redirect()->route('tournaments.index');
    }


    /**
     * Get an array: "sportId" => "sportName", of ALL sports
     *
     * @return Array
     *
     * @author Dessaules Loïc
     */
    private function getDropDownListSports(){
      $sports = Sport::all();

      $sportsList = array();
      for ($i=0; $i < sizeof($sports); $i++) { 
        $sportsList[$sports[$i]->id] = $sports[$i]->name;
      }
      return $sportsList;
    }

    /**
     * Get an array: "sportId" => "sportName"
     * of sports who have one or more court linked
     *
     * @return Array
     *
     * @author Dessaules Loïc
     */
    private function getDropDownListSportsWithCourt(){
      $sportsWithCourt = Sport::join('courts', 'sports.id', '=', 'courts.sport_id')->select('sports.*')->distinct()->get();

      $sportsList = array();
      for ($i=0; $i < sizeof($sportsWithCourt); $i++) { 
        $sportsList[$sportsWithCourt[$i]->id] = $sportsWithCourt[$i]->name;
      }
      return $sportsList;
    }

    /**
     * Get an array: "sportId" => "sportName"
     * of sports who don't have any court linked
     *
     * @return Array
     *
     * @author Dessaules Loïc
     */
    private function getDropDownListSportsWithNoCourt(){
      $sportsWithCourt = Sport::join('courts', 'sports.id', '=', 'courts.sport_id')->select('sports.*')->distinct()->get();

      $sportsWithNoCourt = array();
      foreach (Sport::all() as $sport) {
        $same = false;
        foreach ($sportsWithCourt as $sportWithCourt) {      
          if($sport->id == $sportWithCourt->id){
            $same = true;
          }
        }
        if(!$same){
          $sportsWithNoCourt[] = $sport;
        }
      }

      $sportsList = array();
      for ($i=0; $i < sizeof($sportsWithNoCourt); $i++) { 
        $sportsList[$sportsWithNoCourt[$i]->id] = $sportsWithNoCourt[$i]->name;
      }
      return $sportsList;
    }

    /**
     * Get an array: "teamId" => "teamName"
     * of teams. If we pass a specific tournament, we get all teams who participating + teams who have no tournament linked
     * If we pass null, it's for create a new tournament, we get only the teams who don't have any tounrament linked
     *
     * @param  Array  $tournament
     * @return Array
     *
     * @author Dessaules Loïc
     */
    //teams who are participating to the tournament AND the teams who don't have a tournament linked.
    private function getDropDownListTeams($tournament){
      $teamsWithoutTournament = Team::whereNull('tournament_id')->get();
      
      if($tournament == null){
        $teams =  $teamsWithoutTournament;
      }else{
        $teamsAreParticipating = $tournament->teams;
        // We have to display in the dropdown only the teams who are participating to the tournament AND the teams who don't have a tournament linked.
        $teams = $teamsAreParticipating->merge($teamsWithoutTournament);
      }

      // generate good array => "teamId" => "teamName"
      $dropdownList = array();
      for ($i=0; $i < sizeof($teams); $i++) { 
        $dropdownList[$teams[$i]->id] = $teams[$i]->name; 
      }
      return $dropdownList;
    }


}
