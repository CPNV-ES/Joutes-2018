<?php

namespace App\Http\Controllers;

use App\Pool;
use App\Sport;
use App\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TournamentClassificationController extends Controller
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
    // When accessing the page Tournament Classification index, where we can filter tournaments by sport, and go to their respective classification
    public function index(Request $request)
    {
        //
     $listSports = $this->retrieveSports();

        return view('tournamentClassification.index')->with('sports', $listSports);
    }

    // After choosing a sport in the dropdown list, we return the list of tournaments to display.
    // This also return the list of tournaments where we can duplicate this one, when we are connected as admin of course.
    public function store(Request $request)
    {
        //dd($request->request);
        if (isset($request->request->listTournaments))
        {

        }
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


        // This is for the duplicate function, for the admin
        if(Auth::check())
        {
            if(Auth::user()->role == 'administrator')
            {
                // Retrieve list of tournament name and id
                $listEveryTournaments = Tournament::all('name');

                // Saving the name of every tournaments in an array, to be able to show it in the view
                foreach ($listEveryTournaments as $listEveryTournament)
                {
                    $everyTournaments[] = $listEveryTournament->name;
                }
                //dd($listmyass);
                return view('tournamentClassification.index')->with('tournaments',$listTournaments)->with('sports',$listSports)->with('listTournaments',$everyTournaments);
            }
        }




        return view('tournamentClassification.index')->with('tournaments',$listTournaments)->with('sports',$listSports);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // General Classification
    public function show($id)
    {
        $ranking = array();
        // I retrieve the list of the pool of this tournament, where the final rank is not null. So it will return every ending pool (fun,good and final pools for example).
        $finalPools = Pool::where('pools.tournament_id','=',$id)->whereNotNull('pools.bestFinalRank')->get();

        // Check if any pool is not finished. If it's the case, just return the view and stop here
        if ($finalPools[0]->isFinished != '1')
        {
            // Should be actived. Only disabled for development purpose.
            //return view('tournamentClassification.show');
        }


        // Ranking function only return the total score/total win/total lose of a team in a SINGLE pool. I will try to do a function to have the total of every pools played in a tournament, if I have the time.
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


        // Retrieving all datas from the object to an array. I used this because i cannot order the object, and i cannot count it. If I don't do that here, i would still need to do this in my view, because i cannot just show a multidimensionnal array like this one.
        $rank = array();$vc_array_name = array();
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


        return view('tournamentClassification.show')->with('ranking', $rank);

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
