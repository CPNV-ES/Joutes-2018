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
        $ranking = array();
        // I retrieve the list of the pool of this tournament, where the final rank is not null. So it will return every ending pool (fun,good and final pools for example).
        $finalPools = Pool::where('pools.tournament_id','=',$id)->whereNotNull('pools.bestFinalRank')->get();

        // I need to retrieve the rank of every teams(score,matchs won/loose, ...) in every pools from this tournament.
        // There is a function in the pool model (rankings() that i will use. I use a foreach because this function only accept 1 array
        foreach ($finalPools as $finalPool)
        {
            $ranking[] = $finalPool->rankings();
        }

        // This will add a field bestFinalRank for every recording. The index for the ranking and for the pools are the same, so i can use the counting var of my for for it (I use another var to count every teams in every pools (because there is sometimes 4 or 2 teams by pools, depending on the pool mode)
        for ($i=0;$i<count($finalPools);$i++)
        {
            $teamCount = 0;
            foreach ($finalPools as $pool)
            {
                // Best way I found to only add the rank on existing teams recording inside every pools (I tried to instead use foreach ($finalPools[$i] as $pool) but i can't. Will try to improve this later.
                if (isset($ranking[$i][$teamCount]['team']))
                {
                    // Putting the rank for every team in every pools
                    $ranking[$i][$teamCount]['rank'] = $finalPools[$i]->bestFinalRank + $teamCount;
                    $teamCount++;
                }
            }
        }
        // Retrieving all datas from the object to
        foreach ($ranking as $rankByPool)
        {
            foreach($rankByPool as $rankByTeam)
            {
                $rank[] = $rankByTeam;
            }
        }

        // Used to sort the array by the rank index value.
        foreach ($rank as $key => $row)
        {
            $vc_array_name[$key] = $row['rank'];
        }
        array_multisort($vc_array_name, SORT_ASC, $rank);


        return view('tournamentBySport.show')->with('ranking', $rank);


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
