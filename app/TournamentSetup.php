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

    public function generateTournament($id){

        $tournament = Tournament::find($id);
        $nbTeamPerPool = $tournament->nbTeamPerPool;
        $pools = array();

        //TODO: Add a "nbMaxTeam" and "nbStage" to tournament table
        $nbMaxTeam = 4;
        $nbStages = 4;

        $pools = $this->createPools($tournament, $nbTeamPerPool, $nbMaxTeam, $nbStages);
        $this->createContenders($tournament, pools);
        $this->createGame();
    }

        public function createPools($tournament, $poolSize, $maxTeamsNbr, $nbStages){
        $nbPools = 1 / $poolSize * $maxTeamsNbr; // gives the number of pools to create
        $startTime = $tournament->start_date;
        $endTime = date('H:i:s', strtotime('+2 hours', strtotime($startTime->format('Y-m-d'))));
        $poolsName = $this->createPoolsName($nbPools, $nbStages, $tournament);

        //TODO : Replace this with correct eloquent usage
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

    public function createContenders($tournament, $pools){
        // table fields : rank_in_pool, pool_id, team_id, pool_from_id
        $teams = $tournament->teams;

        for ($i = 0; $i < count($teams); $i++) {
            $contenders = new Contender;

            $contenders->rank_in_pool = null;
            $contenders->pool_id = $pools[0][$i];
            $contenders->team_id = $i;
            $contenders->team_id = $i + 1;
            $contenders->pool_from_id = 'mdr';

            $contenders->save();
        }

        dd(count($pools));
    }

    private function createGame(){

    }
}