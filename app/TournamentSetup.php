<?php
/**
 * User: quentin.neves
 * Date: 01.05.2018
 * Time: 15:33
 * Description:
 *      After tournament validation, this model is used to create all pools, contenders and games of the tournament
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
        $nbPools = 1 / $nbTeamPerPool * $maxTeamsNbr; // gives the number of pools to create
        $this->createPoolsName($nbPools, $nbTeamPerPool);

        // --- Obsolete ---
        // Writes each pool name for the last stage of a tournament
        for ($i = 1; $i <= $nbPools; $i++){
            $lastPlaceOfPool = $nbTeamPerPool * $i;
            $poolNames[3][$i-1] = 'Finale '. ($lastPlaceOfPool) - 3 .'-'. $lastPlaceOfPool; // e.i.  'Finale 1-4'
        }

        for ($i = 0; $i < $nbPools; $i++) {
            $pool = new Pool;
            $pool->start_time = $startDate;
            $pool->end_time = date('d-m-Y H:i', strtotime('+3 days +3 hours', strtotime($startDate->format('Y-m-d'))));
        }

    }

    /**
     * Create all pools names
     *
     * @param $nbPools          - Total number of pool in tournament
     * @param $nbTeamsPerPool   - Number of teams per pool
     * @return Pools names as an Array of string
     *
     * @author Quentin Neves
     */
    private function createPoolsName($nbPools, $nbTeamsPerPool){
        // TODO: Check if there's always 4 stages in a tournament and adapt the code accordingly
        $nbStages = 4; // default value
        $poolNames = array();

        // for loop for each stage
        for ($s = 0; $s < $nbStages; $s++) {
            for ($p = 0; $p < $nbPools; $p++) {
                switch ($s){
                    // case 0 = stage 1
                    case 0:
                        $poolNames[$s][$p] = 'Poule'.$s;
                        break;
                    case 1:
                        if ($p < $nbPools / 2) $poolNames[$s][$p] = 'WIN'.$s;
                        else $poolNames[$s][$p] = 'FUN'.$p;
                        break;
                    case 2:
                        if ($p < $nbPools / 2) $poolNames[$s][$p] = 'BEST'.$s;
                        else $poolNames[$s][$p] = 'GOOD'.$p;
                        break;
                    // final pool
                    case 3:
                        $lastPlaceOfPool = $nbTeamsPerPool * $p;
                        $poolNames[$s][$p] = 'Finale '. ($lastPlaceOfPool) - ($nbTeamsPerPool- 1) .'-'. $lastPlaceOfPool; // e.i. 'Finale 1-4' for a 4 teams per pool tournament
                        break;
                }
            }
        }
        return $poolNames;
    }

    private function createContenders(){

    }

    private function createGame(){

    }
}