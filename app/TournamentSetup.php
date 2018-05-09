<?php
/**
 * Created by PhpStorm.
 * User: quentin.neves
 * Date: 01.05.2018
 * Time: 15:33
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tournament;

class TournamentSetup {
    public function generateTournament($id){

        $tournament = Tournament::find($id);
        $nbTeams = count($tournament->teams());
        $nbTeamPerPool = $tournament->nbTeamPerPool;
        $maxTeamsNbr = 4;
        // get max team number per tournament


        $this->createPools($tournament->start_date, $nbTeamPerPool, $maxTeamsNbr, $tournament);
        $this->createContenders();
        $this->createGame();
    }

    // TODO : Put this method as private
    public function createPools($startDate, $nbTeamPerPool, $maxTeamsNbr){
        $poolNbr = 1 / $nbTeamPerPool * $maxTeamsNbr; // gives the number of pools to create
        $poolNames = array();
        $poolNames[0] = ['A','B','C','D','E','F','G','H'];
        $poolNames[1] = ['WIN1','WIN2','WIN3','WIN4','FUN1','FUN2','FUN3','FUN4'];
        $poolNames[2] = ['BEST1','BEST2','BEST3','BEST4','GOOD1','GOOD2','GOOD3','GOOD4'];

        // Writes each pool name for the last stage of a tournament
        for ($i = 1; $i <= $nbTeamPerPool; $i++){
            $lastPlaceOfPool = $nbTeamPerPool * $i;
            $poolNames[3][$i-1] = 'Finale '. ($lastPlaceOfPool) - 3 .'-'. $lastPlaceOfPool; // e.i.  'Finale 1-4'
        }

        for ($i = 0; $i < $poolNbr; $i++) {
            $pool = new Pool;
            $pool->start_time = $startDate;
            $pool->end_time = date('d-m-Y H:i', strtotime('+3 days +3 hours', strtotime($startDate->format('Y-m-d'))));
        }

    }

    private function createContenders(){

    }

    private function createGame(){

    }
}