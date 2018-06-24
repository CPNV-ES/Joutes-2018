<?php
/**
 * User: quentin.neves
 * Date: 19.06.2018
 * Description:
 *      After tournament validation, this model is used to create all pools, contenders and games of the tournament
 *
 * Warning:
 *      This generation is not implemented yet, it needs to be tested with different tournaments setups and
 *      tournament creation form need to be updated with the validations rules
 */

namespace App;

class TournamentSetup {

    /**
     * Decide how to create a tournament based on a few data
     *
     * @param $id       - Current tournament id
     *
     * @author Quentin Neves
     */
    public function generateTournament($id){

        $tournament = Tournament::find($id);

        $nbStages = $tournament->nb_stages;
        $nbTeamsPerPool = $tournament->nbTeamPerPool;
        $nbPoolsPerStage = (count($tournament->teams)/$nbTeamsPerPool);

        $poolsName = $this->createPoolsName($nbPoolsPerStage, $nbStages, $tournament);

        // TODO: $startTime and $endTime need to be ajusted accordingly to the current stage
        $startTime = date("H:i:s", strtotime($tournament->startTime));
        $endTime = date('H:i:s', strtotime('+3 hours', strtotime($startTime)));

        $pools = array();
        $contenders = array();

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

                // TODO : adapt for each pool configuration (sometimes there's 2 Pools of each rank)
                // Set the rank required in the previous pool to join this one
                if ($c % $nbPoolsPerStage == 0) $rank++;
                $contender->rank_in_pool = ($s) ? $rank : null;

                // Set the team ids only for the first stage
                $contender->team_id = ($s) ? null : $tournament->teams[$c]->id;

                // Set the pool_id for the current pool and the one which the team comes from
                $contender->pool_id = $pools[$s][$poolIndex]->id;
                $contender->pool_from_id = ($s) ? $pools[$s-1][$poolIndex]->id : null; // -1 to select pool from the previous stage

                // Increase the poolIndex after setting it to avoid index undefined
                if (($c+1) % $nbTeamsPerPool == 0) $poolIndex++;

                $contender->save();

                $contenders[$s][$c] = $contender;
            }

            // Game creation

            for ($p = 0; $p < $nbPoolsPerStage; $p++) { // For each Pool
                for ($c = 0; $c < $nbTeamsPerPool; $c++) {  // For each team in this pool
                    for ($g = 1; $g < $nbTeamsPerPool - $c; $g++) { // For each not already created match for this contender

                        $game = new Game();

                        // TODO : adapt time
                        $game->date = $tournament->start_date->format("Y/m/d");
                        $game->start_time = $tournament->start_date->format("h:i:s");

                        // Define which contender id to use
                        $game->contender1_id = $contenders[$s][$c + ($p * $nbTeamsPerPool)]->id; // +($p * $nbTPP) to use another "set" of contender, see documentation for more details
                        $game->contender2_id = $contenders[$s][$c + $g + ($p * $nbTeamsPerPool)]->id; // +$g to ignore already created games

                        $game->score_contender1 = null;
                        $game->score_contender2 = null;

                        // TODO : select a unused court
                        $game->court_id = 1;

                        $game->save();
                    }
                }
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

        return $poolsName;
    }


}