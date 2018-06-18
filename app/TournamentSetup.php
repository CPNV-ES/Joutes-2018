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
        $nbStages = $tournament->nb_stages;
        $nbTeamsPerPool = $tournament->nbTeamsPerPool;
        $nbPoolsPerStage = (count($tournament->teams)/$nbTeamsPerPool);
        $nbGamesPerStage = 0;
        for ($i = 1; $i <= $nbTeamsPerPool; $i++) $nbGamesPerStage += $nbTeamsPerPool - $i; // Calculates the number of games in a pool



        // Readability variables
        // TODO: $startTime and $endTime need to be ajusted accordingly to the current stage
        $startTime = date("H:i:s", strtotime($tournament->startTime));
        $endTime = date('H:i:s', strtotime('+2 hours', strtotime($startTime)));
        $pools = array();
        $contenders = array();

        $poolsName = $this->createPoolsName($nbPoolsPerStage, $nbStages, $tournament);

        // For each stage
        for ($s = 0; $s < $nbStages; $s++) {
            // reseting variables
            $rank = 0;
            $poolIndex = 0;

            // Pool creation
            for ($p = 0; $p < $nbPoolsPerStage; $p++) {

                $pool = new Pool;

                $pool->start_time = $startTime;
                $pool->end_time = $endTime;
                $pool->poolName = $poolsName[$s][$p];
                $pool->stage = $s;
                $pool->poolSize = $nbTeamsPerPool;
                $pool->isFinished = false;
                $pool->tournament_id = $tournament->id;
                $pool->mode_id = 1;
                $pool->gameType_id = 1;

                $pool->save();

                $pools[$s][$p] = $pool;
            }

            // Contenders creation
            for ($c = 0; $c < ($nbTeamsPerPool*$nbPoolsPerStage); $c++) {

                $contender = new Contender();

                if ($c % $nbPoolsPerStage == 0) $rank++;
                $contender->rank_in_pool = ($s) ? $rank : null;

                $contender->team_id = ($s) ? $tournament->teams[$c]->id : null;

                $contender->pool_id = $pools[$s][$poolIndex]->id;
                $contender->pool_from_id = ($s) ? $pools[$s-1][$poolIndex]->id : null;
                if (($c+1) % $nbTeamsPerPool == 0) $poolIndex++;

                $contender->save();

                $contenders[$s][$c] = $contender;
            }

            $gamesCount = 0;
            // Game creation
            for ($p = 0; $p < $nbPoolsPerStage; $p++) {
                for ($t = 0; $t < $nbTeamsPerPool; $t++) {
                    for ($g = 1; $g < $nbTeamsPerPool - $t; $g++) {

                        $game = new Game();

                        // TODO : adapt time
                        $game->date = date('Y-m-d', strtotime('06/18/2018')); // date('Y-m-d', $tournament->start_date);
                        $game->start_time = date('H:i:s',time());

                        $game->contender1_id = $contenders[$s][$t+($p * $nbTeamsPerPool)]->id;
                        $game->contender2_id = $contenders[$s][$t+$g+($p * $nbTeamsPerPool)]->id;

                        $game->score_contender1 = null;
                        $game->score_contender2 = null;

                        // TODO : select a unused court
                        $game->court_id = 1;

                        $game->save();
                        $gamesCount++;
                        $games[$s][$gamesCount] = $game;
                    }
                }
            }
        }
        dd($games);
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

        return $poolsName;
    }
}