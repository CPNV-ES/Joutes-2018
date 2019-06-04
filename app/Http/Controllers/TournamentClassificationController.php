<?php

namespace App\Http\Controllers;

use App\Event;
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

                // Retrieve list of events, used for the duplicate fonctionnality. I should recover the datas in another array, because the display in the view isn't perfect (0 -> event 1, 1 -> event 2)
                $listEvents = Event::all('name');


                // Return the view with the datas for the admin
                return view('tournamentClassification.index')->with('tournaments',$listTournaments)->with('sports',$listSports)->with('listTournaments',$everyTournaments)->with('events', $listEvents);
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



        /* This should work but it's not the case ...
        $finalPools = DB::table('pools')
            ->whereNotNull('pools.bestFinalRank')
            ->where('pools.tournament_id','=',$id)
            ->join('tournaments','pools.tournament_id','=','tournaments.id')
            // Error when joining this table .............
            ->join('teams','tournaments.id','=','teams.tournament_id')
            //->select('pools.id as pool_id','tournaments.name as tournament', 'teams.name as team', 'pools.stage', 'pools.bestFinalRank')
            ->get();

        // This should also work
        $finalPools = Tournament::with('pools')->join('teams','tournaments.id','=','teams.tournament_id')->where('pools.tournament_id','=',$id)->whereNotNull('pools.bestFinalRank')->get();


        $ranking = array();
        $poolRank = 0;
        $nbPoolsInStage = 0;
        $previousPool = 0;
        dd($finalPools->toArray());
        foreach ($finalPools as $pool)
        {
            // The rank of the team of the participants is reset every time the function "execute" a new pool.
            // This is done with the $previousPool var, which store the id of the pool, but a the end of the loop, so the previous one here.
            if ($pool->pool_id != $previousPool)
            {
                $poolRank = 0;
            }

            //This is the gap between participants in a pool, depending on the stage of the pool
            if ($pool->stage == '2') { $nbPoolsInStage = 4; }
            if ($pool->stage == '3') { $nbPoolsInStage = 2; }
            if ($pool->stage == '4') { $nbPoolsInStage = 1; }

            $ranking[$poolRank]['tournament'] = $pool->tournament;
            $ranking[$poolRank]['team'] = $pool->name;
            $ranking[$poolRank]['stage'] = $pool->stage;
            $ranking[$poolRank]['score'] = $pool->bestFinalRank;
            // only used in dev, because in reality this value should be always set to 1, according to the request
            if ($pool->bestFinalRank != 0)
            {
                // The rank is calculated with the bestFinalRank value, which is equal to the rank of the first of the current pool. We add to this the rank of the team in pool multiply  by the number of pools in this stage
                $ranking[$poolRank]['rank'] = $pool->bestFinalRank + ($nbPoolsInStage * $poolRank);
            }
            else
            {
                $ranking[$poolRank]['rank'] = 'Pas terminé';
            }
            $previousPool = $pool->pool_id;
            $poolRank++;

        }

        */

        $ranking = array();
        // I retrieve the list of the pool of this tournament, where the final rank is not null. So it will return every ending pool (fun,good and final pools for example).
        $finalPools = Pool::where('pools.tournament_id','=',$id)->whereNotNull('pools.bestFinalRank')->get();

        // Check if any pool is not finished. If it's the case, just return the view and stop here
        if (isset($finalPools[0]))
        {
            if ($finalPools[0]->isFinished != '1')
            {
                return view('tournamentClassification.show');
            }
        }
        else
        {
            return view('tournamentClassification.show');
        }

        // Ranking function only return the total score/total win/total lose of a team in a SINGLE pool.
        // I will try to do a function to have the total of every pools played in a tournament, if I have the time.
        // I could join the tournaments and teams tables to have access to the team name, but with this method, i will be able to add new column (matchs won/lose for a team, in this tournament) without changing a lot of code
        // There is a function in the pool model (rankings() that i will use. I use a foreach because this function only accept 1 array
        foreach ($finalPools as $finalPool)
        {
            $ranking[] = $finalPool->rankings();
        }
        // This will add a field bestFinalRank for every recording. The index for the ranking and for the pools are the same, so i can use the counting var of my for for it (I use another var to count every teams in every pools (because there is sometimes 4 or 2 teams by pools, depending on the pool mode)
        for ($i=0;$i<count($finalPools);$i++)
        {
            $teamCount = 0;
            $poolGap = 0;
            foreach ($finalPools as $pool)
            {
                // Best way I found to only add the rank on existing teams recording inside every pools (I tried to instead use foreach ($finalPools[$i] as $pool) but i can't. Will try to improve this later.
                if (isset($ranking[$i][$teamCount]['team']))
                {
                    // Putting the rank for every team in every pools
                    if ($finalPools[$i]->stage == '2') { $poolGap = 4; } //This is the gap between participants in a pool, depending on the stage of the pool
                    if ($finalPools[$i]->stage == '3') { $poolGap = 2; }
                    if ($finalPools[$i]->stage == '4') { $poolGap = 1; }
                    // The final best is is the one in the DBB, and I add the poolGap*position of the team
                    $ranking[$i][$teamCount]['rank'] = $finalPools[$i]->bestFinalRank + ($poolGap * $teamCount);
                    $teamCount++;
                }
            }
        }
        // Retrieving all datas from the object to an array. I used this because i cannot order the object, and i cannot count it. If I don't do that here, i would still need to do this in my view, because i cannot just show a multidimensionnal array like this one.
        $rank = array(); $vc_array_name = array();
        foreach ($ranking as $rankByPool)
        {
            foreach($rankByPool as $rankByTeam)
            {
                $rank[] = $rankByTeam;
            }
        }
        // Used to sort the array by the rank index value. Inspired by code on PHP.net and StackOverflow
        foreach ($rank as $key => $row)
        {
            $vc_array_name[$key] = $row['rank'];
        }
        array_multisort($vc_array_name, SORT_ASC, $rank);


        return view('tournamentClassification.show')->with('ranking', $rank);

    }
}
