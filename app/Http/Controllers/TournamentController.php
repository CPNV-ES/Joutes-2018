<?php

namespace App\Http\Controllers;

use App\Http\Response\Transformers\ParticipantTeamTransformer;
use App\Tournament;
use App\Pool;
use App\Team;
use App\News;
use App\Participant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    /**
     * Display a listing of the tournaments.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function index()
    {
        $tournaments = Tournament::all()->sortBy("start_date");

        foreach ($tournaments as $tournament) {
            if (empty($tournament->img)) {
                $tournament->img = 'default.jpg';
            }
        }

        return view('tournament.index', array(
            "tournaments" => $tournaments,
            "fromEvent" => false
        ));
    }

    /**
     * Display the specified tournament.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc, Davide Carboni
     */
    public function show(Request $request, $id)
    {
        $tournament = Tournament::find($id);

        if ($request->ajax())
        {
            // Check if the tornament is Full, no more teams are accepted
            if ($request->input("isFull") == "isFull") {
                if (($tournament->isComplete()) || ($tournament == null)) return 1;
                else return 0;
            }
        }

        $pools = $tournament->pools;
        $totalStage = 0;
        foreach ($pools as $pool) {
            if($pool->stage > $totalStage){
                $totalStage = $pool->stage;
            }
        }
        $tournament =  Tournament::find($id);

        $news = News::where('tournament_id', $id)
                            ->OrderBy('creation_datetime', 'desc')
                            ->get();
        $teams = Team::where('tournament_id', $id)->get();


        foreach ($teams as $team)
        {
            $listTeams[] = $team['id'];

        }


        // Requete QueryBuilder qui permet de récupérer la liste des potentiels manager du tournoi en cours
        $participants = DB::table('tournaments')
            ->join('teams', 'tournament_id', '=', 'tournaments.id')
            ->join('participant_team','team_id','=','teams.id')
            ->join('participants','participant_id','=','participants.id')
            ->select('participants.id','first_name','last_name')
            ->where('tournaments.id','=',$id)
            ->orderBy('first_name')
            ->get();

        // Arrange les données de la requête précédente dans un tableau, avec [participant_id] => "Prénom Nom".
        $participantsFullName = array();
        for ($i=0;$i<count($participants);$i++)
        {
            $participantsFullName[$participants[$i]->id] = $participants[$i]->first_name." ".$participants[$i]->last_name;
        }

        return view('tournament.show')->with('tournament', $tournament)
                                      ->with('pools', $pools)
                                      ->with('news', $news)
                                      ->with('participants', $participantsFullName)
                                      ->with('totalStage', $totalStage);
    }

    public function postNews(Request $data, $id)
    {

        $newsString = $data->get('news');

        $status = $data->get('status');

        $news = new News;

        $news->content = $newsString;
        if (isset($status))
        {
            $news->isUrgent = '1';
        }
        else
        {
            $news->isUrgent = '0';
        }

        $news->tournament_id = $id;

        $news->save();

        return redirect('/tournaments/'.$id);
    }

    // Fonction permettant de désigner une personne inscrite au tournoi sélectionné comme manager de celui-ci.
    // La valeur isTournamentManager de la table participant_team passe à "1", pour l'utilisateur dont l'id est celui selectionné à la vue admin show.tournament
    public function postManager(Request $data, $id)
    {

        DB::table('participant_team')
            ->where('participant_id','=',$data->get('userID'))
            ->join('teams', 'team_id', '=', 'teams.id')
            ->join('tournaments','tournament_id','=','tournaments.id')
            ->where('tournament_id','=',$id)
            ->update(['isTournamentManager' => 1]);


        return redirect('/tournaments/'.$id);
    }
}
