<?php

namespace App\Http\Controllers;

use App\Pool;
use App\Sport;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

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

    // I retrieve the list of the pool where the final rank is not null. So it will return every ending pool.
    //$finalPools = Pool::whereNotNull('pools.bestFinalRank')->where('pools.tournament_id','=',$id)->select('pools.id','pools.poolName','pools.bestFinalRank');
    $finalPools = Pool::find(1);
    // Doesn't work for now ...
    //$finalPools = Pool::whereNotNull('pools.bestFinalRank')->where('pools.tournament_id','=',$id)->select('pools.id','pools.poolName','pools.bestFinalRank');


    // I need to retrieve the rank of everyone in every pools from before. There is a function in the pool model (rankings())
    $ranking = $finalPools->rankings();




        echo '<div style="margin-left:300px;">';
            dd ($ranking);
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
