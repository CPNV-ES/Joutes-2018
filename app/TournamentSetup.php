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

    private function createPools($startDate, $nbTeamPerPool, $maxTeamsNbr){
        $poolNbr = 1 / $nbTeamPerPool * $maxTeamsNbr; // gives the number of pools to create

        for ($i = 0; $i < $poolNbr; $i++) {
            $pool = new Pool;
            $pool->start_time = $startDate;
            $pool->end_time = 0;
        }

    }

    private function createContenders(){

    }

    private function createGame(){

    }
}