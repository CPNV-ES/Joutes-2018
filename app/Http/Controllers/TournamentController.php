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
use Illuminate\Support\Facades\Auth;
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

        // Je récupère la liste des news de ce tournoi, et les retournes à la vue.
        $news = News::where('tournament_id', $id)
                        ->OrderBy('creation_datetime', 'desc')
                        ->get();


        // Requete QueryBuilder qui permet de récupérer la liste des potentiels manager du tournoi en cours (pour vue admin)
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


        // Récupère et arrange les noms de chaque tournois existants. (pour vue admin, qui permet de dupliquer un tournoi)
        $listTournaments = Tournament::all('name');
        $listNameTournaments = array();
        for ($i=0;$i<count($listTournaments);$i++)
        {
            $listNameTournaments[] = $listTournaments[$i]['name'];
        }


        // Je récupère la liste des responsables (managers) de ce tournoi, et les retournes à la vue
        $managers = DB::table('tournaments')
            ->join('teams', 'tournament_id', '=', 'tournaments.id')
            ->join('participant_team','team_id','=','teams.id')
            ->join('participants','participant_id','=','participants.id')
            ->select('first_name','last_name')
            ->where('tournaments.id','=',$id)
            ->where('participant_team.isTournamentManager','=','1')
            ->orderBy('first_name')
            ->get();


        return view('tournament.show')->with('tournament', $tournament)
                                      ->with('pools', $pools)
                                      ->with('news', $news)
                                      ->with('nameTournaments', $listNameTournaments)
                                      ->with('participants', $participantsFullName)
                                      ->with('managers', $managers)
                                      ->with('totalStage', $totalStage);
    }

    // Fonction permettant de poster une nouvelle news. Normalement en tant que manager du tournoi en cours (SAML non fonctionelle, uniquement avec compte admin pour l'instant).
    public function postNews(Request $data, $id)
    {
        // Je recupère les données du formulaire, envoyées depuis la vue.
        $newsString = $data->get('news');
        $status = $data->get('status');

        // J'enregistre la nouvelle news en utilisant Eloquent, en orienté objet.
        $news = new News;
        $news->content = $newsString;
        $news->tournament_id = $id;
        $news->isUrgent = '0';

        // Si la news est jugée urgente par le manager
        if (isset($status))
        {
            $news->isUrgent = '1';

            // Envoie un mail qui permet d'envoyer un sms à l'utilisateur, si il a enregistré un numéro.
            //Avant de l'envoyer je dois récupérer le numéro de téléphone de l'utilisateur
            $listPhonesNumbers = array();
            $mailTo = array();
            $participantsPhoneNumber = DB::table('participants')
                                            ->join('participant_team', 'participant_id', '=', 'participants.id')
                                            ->join('teams', 'team_id', '=', 'teams.id')
                                            ->join('tournaments','tournament_id','=','tournaments.id')
                                            ->select('participants.phone_number')
                                            ->where('tournaments.id',$id)
                                            ->get();
            // je récupère uniquement ce que j'ai besoin, dans un tableau simple.
            foreach ($participantsPhoneNumber as $participantPhoneNumber)
            {
                $listPhonesNumbers[] = $participantPhoneNumber->phone_number;
            }
            # To send an SMS to one person, we must send an email to <number>@sms.admin.ch
            # The number must start with a 0 and have no spaces (obviously)
            # (to be verified:) The email must be sent from inside the cpnv network to be accepted
            // Paramètres SMTP du réseau du cpnv. Doit être changé ici ou dans php.ini par les bons paramètres, lors de la mise en ligne du site.
            ini_set('SMTP','mail.cpnv.ch');
            ini_set('sendmail_from','no-reply@joutes-cpnv-test.com');

            $mailHeader = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $mailHeader  .= "From: NO-REPLY<no-reply@joutes-cpnv-test.com>" . "\r\n";

            // Réarrange les données pour ne garder que les numéros de tél, et plus les champs vide. Je rajoute également le reste de l'adresse email.
            for ($i=0;$i<count($listPhonesNumbers);$i++)
            {
                if ($listPhonesNumbers[$i] != null)
                {
                    $mailTo[] = "0".$listPhonesNumbers[$i]."@sms.admin.ch";
                }

            }

            // Envoie un mail à chaque participant qui est inscrit à ce tournoi et a renseigné son numéro de téléphone. Compte le nombre de mail envoyés.
            $mailSent = 0;
            for ($i=0;$i<count($mailTo);$i++)
            {
                if (@mail($mailTo[$i],$news->content, $mailHeader))
                {
                    $mailSent +=1;
                }
            }
            // Pas eu le temps de comprendre comment fonctionne la gestion des notifications, déjà existant sur le projet, et de l'utiliser ici pour notifier l'admin.
            //print_r ($mailSent." mails envoyés.");
        }
        // Envoie la requête sur le serveur MySQL pour créer la news.
        $news->save();

        return redirect('/tournaments/'.$id);
    }

    // Fonction permettant de désigner une personne inscrite au tournoi sélectionné comme manager de celui-ci.
    // La valeur isTournamentManager de la table participant_team passe à "1", pour l'utilisateur dont l'id est celui selectionné à la vue admin show.tournament
    public function postManager(Request $data, $id)
    {

        // Je met à jour l'utilisateur séléctionné comme manager du tournoi en cours (dans la table intermediaire) (vue admin)
        DB::table('participant_team')
            ->where('participant_id','=',$data->get('userID'))
            ->join('teams', 'team_id', '=', 'teams.id')
            ->join('tournaments','tournament_id','=','tournaments.id')
            ->where('tournament_id','=',$id)
            ->update(['isTournamentManager' => 1]);


        return redirect('/tournaments/'.$id);
    }

    public function postDuplicateTournament(Request $data, $id)
    {

        // Je récupère les informations importantes du tournoi à dupliquer.
        $thisTournament = DB::table('tournaments')
            ->where('tournaments.id','=',$id)
            // Je devrais récupérer seulement 1 enregistrement, mais j'en ai 192 pareilles, et je ne comprend pas pourquoi. Je limite à 1 en attendant de trouver un meilleur moyen
            ->limit(1)
            // Je dois récupérer les pools du tournois, et les dupliquer pour le nouveau tournoi
            //->join('pools','pools.tournament_id','=','tournaments.id')
            //->join('game_types','game_type_id','=','game_types.id')
            //->join('pool_modes','mode_id','=','pool_modes.id')
            //->join('courts','courts.sport_id','=','sports.id')
            ->select('tournaments.id','tournaments.name', 'tournaments.start_date', 'tournaments.img', 'tournaments.event_id', 'tournaments.sport_id', 'tournaments.end_date', 'tournaments.max_teams')
            ->get();


       // Je met à jour le tournoi choisi, avec les paramètres du tournoi à dupliquer.
         DB::table('tournaments')
          ->where('tournaments.id','=',$data['tournamentID']+1)
          ->update(['tournaments.start_date' => $thisTournament[0]->start_date, 'tournaments.end_date' => $thisTournament[0]->end_date, 'tournaments.sport_id' => $thisTournament[0]->sport_id, 'tournaments.event_id' => $thisTournament[0]->event_id, 'tournaments.img' => $thisTournament[0]->img, 'tournaments.max_teams' => $thisTournament[0]->max_teams]);


        return redirect('/tournaments/');
    }
}
