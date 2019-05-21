<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Tournament;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class TournamentBySportController extends Controller
{

    // Function used to retrieve the list of all the sports, and return the name with the id
    public function retrieveSports()
    {
        $listSports = Sport::all();
        $listSportsName = array();
        foreach ($listSports as $sports)
        {
            $listSportsName[$sports->id] = $sports->name;
        }
        return $listSportsName;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
     $listSports = $this->retrieveSports();

        return view('tournamentBySport.index')->with('sports', $listSports);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listTournaments = array();

        $listSports = $this->retrieveSports();
        $idSport = $request->request->get('listSports');

        // Impossible de sortir la valeur de l'objet. Pourtant la requete est bonne
        // $listTournaments = Sport::with('tournaments')->where('sports.id',$idSport)->get();
        // dd($listTournaments['0']->tournaments());

        $listTournaments = DB::table('sports')
                                ->where('sports.id','=',$idSport)
                                ->join('tournaments','tournaments.sport_id','=','sports.id')
                                ->select('tournaments.id','tournaments.name as name','sports.name as sport', 'tournaments.start_date', 'tournaments.end_date' , 'tournaments.img')
                                ->orderByRaw('tournaments.start_date DESC')
                                ->get();


        return view('tournamentBySport.index')->with('tournaments',$listTournaments)->with('sports',$listSports);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        /*
        $classification = DB::table('pools')
                                    ->join('tournaments','tournaments.id','=','pools.tournament_id')
                                    ->join('teams','tournaments.id','=','teams.tournament_id')
                                    ->join('participant_team','teams.id','=','participant_team.team_id')
                                    ->join('participants','participants.id','=','participant_team.participant_id')
                                    ->join('contenders','pools.id','=','contenders.pool_id')
                                    ->join('games','contenders.id','=','games.contender1_id')
                                    ->join('games as games2','contenders.id','=','games2.contender2_id')
                                    ->select
                                    (
                                        'participants.id as participant_id', 'participants.first_name','participants.last_name',
                                        'teams.name'



                                    )
                                    ->get();


        $total_1 = DB::table('games')->sum('games.score_contender1');
        */

        ;



        echo '<div style="margin-left:300px;">';
            var_dump ($id);
        echo '</div>';

        return view('tournamentBySport.show');


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
