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

        $tournament = Tournament::find($id);
        $poolSize = $tournament->nbTeamPerPool;

        //TODO: Add a "nbMaxTeam" and "nbStage" to tournament table and delete these variable
        $nbMaxTeam = 32;
        $nbStages = 4;

        $pools = $this->createPools($tournament, $poolSize, $nbMaxTeam, $nbStages);
        $contenders = $this->createContenders($tournament);
        $this->createGame();
    }

    /**
     * Create all pools required for the tournament
     *
     * @param $tournament   - Current tournament
     * @param $poolSize     - Number of teams in a pool
     * @param $maxTeamsNbr  - Total number of teams participating to the tournament
     * @param $nbStages     - Total number of stages in the tournament
     * @return $pools       - Array of created pools, used for contenders creation
     *
     * @author Quentin Neves
     */
    private function createPools($tournament, $poolSize, $maxTeamsNbr, $nbStages){
        // Calculate the required number of pools
        $nbPools = 1 / $poolSize * $maxTeamsNbr;

        $startTime = $tournament->start_date;

        // TODO: Adapt the strtotime depending on tournament's sport
        $endTime = date('H:i:s', strtotime('+2 hours', strtotime($startTime->format('Y-m-d'))));

        $poolsName = $this->createPoolsName($nbPools, $nbStages, $tournament);

        for ($stage = 1; $stage <= $nbStages; $stage++){
            for ($poolCount = 1; $poolCount <= $nbPools; $poolCount++){

                $pool = new Pool;

                $pool->start_time = date("H:i:s", strtotime($startTime));
                $pool->end_time = $endTime;
                $pool->poolName = $poolsName[$stage][$poolCount];
                $pool->stage = $stage;
                $pool->poolSize = $poolSize;
                $pool->isFinished = false;
                $pool->tournament_id = $tournament->id;
                $pool->mode_id = 1;
                $pool->gameType_id = 1;

                $pool->save();

                $pools[$stage][$poolCount] = $pool;
            }
        }
        return $pools;
    }

    /**
     * Create all pools names
     *
     * @param $nbPools          - Total number of pool in tournament
     * @param $tournament       - Current tournament
     * @return Pools names as an Array of string
     *
     * @author Quentin Neves
     */
    private function createPoolsName($nbPools, $nbStages, $tournament){
        $poolsName = array();

        // create each pool name, i.e. "Badminton 1-3"
        for ($stage = 1; $stage <= $nbStages; $stage++) { for ($pool = 1; $pool <= $nbPools; $pool++) { $poolsName[$stage][$pool] = $tournament->getSport() ." ".$stage."-".$pool; } }

        return $poolsName;
    }

    public function createContenders($tournament) {
        // table fields : rank_in_pool, pool_id, team_id, pool_from_id
        $teams = $tournament->teams;
        $pools = $this->createPools($tournament, 4, 32, 4);  //$tournament->pools;

        $teamPerPool = $tournament->teamPerPool;

        $stages = 4; //$tournament->stages;
        $count = 1; // Count the number of iterations in team for loop

        $poolIndex = 1; // defines the index of the pool to get the id from
        $rank = 1; // defines the contender's rank in the current pool

        for ($s = 0; $s < $stages; $s++){
            for ($t = 0; $t < 32; $t++) { // Replace 32 by count($teams) but with seed there is 35 teams in a tournament for 32, really guys ?
                $contender = new Contender();
                $contender->rank_in_pool = ($s == 0) ? null : $rank; // in first stage, the rank in pool must be null
                $contender->team_id = ($s == 0) ? $teams[$t]->id : null;

                $contender->pool_id = $pools[$s+1][$poolIndex]->id;
                $contender->pool_from_id = ($s > 0) ? $pools[$s][$poolIndex]->id : null;

                if ($count >= 8) {
                    $rank++;
                    $count = 1;
                }
                else $count++;
                if ($rank > 4) $rank = 1;

                ($poolIndex >= 8) ? $poolIndex = 1 : $poolIndex++;

                $contender->save();

                $contenders[$s][$t] = $contender;
            }
            $rank = 1;

        }

        dd($contenders[1]);
    }

    private function createGame(){

    }
}