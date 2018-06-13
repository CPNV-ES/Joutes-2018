<?php
/**
 * User: quentin.neves
 * Date: 12.06.2018
 * Description:
 *      After tournament validation, this model is used to create all pools, contenders and games of the tournament
 */

namespace App;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Model;
use App\Tournament;
use Mockery\Exception;
use PhpParser\Node\Expr\Array_;

class TournamentSetup {

    /**
     * Main function that decide how to create a tournament
     *
     * @param $tournament       - Current tournament
     * @param $poolSize         - Number of teams in a pool
     * @param $pools            - Array of created pools, used in contenders creation
     *
     * @author Quentin Neves
     */
    public function generateTournament($id){

        // Count variables init
        $tournament = Tournament::find($id);
        $nbStages = 4; // $tournament->nbStages;
        $nbPoolsPerStage = 8; // $tournament->nbPoolsPerStage
        $nbTeamsPerPool = 4; // $tournament->nbTeamsPerPool
        $nbGamesPerStage = 0;
        for ($i = 1; $i <= $nbTeamsPerPool; $i++) $nbGamesPerStage =+ $nbTeamsPerPool - $i; // Calculates the number of games in a pool


        // Readability variables
        // TODO: $startTime and $endTime need to be ajusted accordingly to the current stage
        $startTime = date("H:i:s", strtotime($tournament->startTime));
        $endTime = date('H:i:s', strtotime('+2 hours', strtotime($startTime)));

        $poolsName = $this->createPoolsName($nbPoolsPerStage, $nbStages, $tournament);

        // For each stage
        for ($s = 0; $s < $nbStages; $s++) {
            // Pool creation
            for ($p = 0; $p < $nbPoolsPerStage; $p++) {
                
            }

            // Contenders creation
            for ($c = 0; $c < ($nbTeamsPerPool*$nbPoolsPerStage); $c++) {

            }

            // Game creation
            for ($g = 0; $g < $nbGamesPerStage; $g++) {

            }
        }
    }

    /**
     * Create all pools names
     *
     * @param $nbPools          - Total number of pools in tournament
     * @param $nbStages         - Total number of stages in tournament
     * @param $tournament       - Current tournament
     * @return Pools names as an Array of string
     *
     * @author Quentin Neves
     */
    private function createPoolsName($nbPools, $nbStages, $tournament){
        $poolsName = array();

        // create each pool name, i.e. "Badminton 1-3"
        for ($stage = 0; $stage < $nbStages; $stage++) { for ($pool = 0; $pool < $nbPools; $pool++) { $poolsName[$stage][$pool] = $tournament->getSport() ." ".($stage + 1)."-".($pool + 1); } }
        dd($poolsName);

        return $poolsName;
    }
}