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


        $this->createPools($tournament, $nbTeamPerPool, $maxTeamsNbr);
        $this->createContenders();
        $this->createGame();
    }

    // TODO : Put this method as private
    public function createPools($tournament, $nbTeamPerPool, $maxTeamsNbr){
        $nbPools = 1 / $nbTeamPerPool * $maxTeamsNbr; // gives the number of pools to create
        $nbStages = 4;
        $startTime = $tournament->start_date;
        $endTime = date('H:i:s', strtotime('+2 hours', strtotime($startTime->format('Y-m-d'))));
        $poolsName = $this->createPoolsName($nbPools, $nbStages, $tournament);
        //dd($poolsName);

        for ($stage = 1; $stage <= $nbStages; $stage++){
            for ($pool = 1; $pool <= $nbPools; $pool++){
                $truc = [
                    'start_time' => date("H:i:s", strtotime($startTime)),
                    'end_time' => $endTime,
                    'poolName' => $poolsName[$stage][$pool],
                    'stage' => $stage,
                    'poolSize' => $nbTeamPerPool,
                    'isFinished' => 0,
                    'tournament_id' => $tournament->id,
                    'mode_id' => 1,
                    'gameType_id' => 1
                ];
                $thepool[$stage][$pool] = Pool::create($truc);
            }
        }
        dd($thepool);

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
        // TODO: Adapt to x stages
        $poolsName = array();
        $sport = $tournament->getSport();

        for ($stage = 1; $stage <= $nbStages; $stage++) {
            for ($pool = 1; $pool <= $nbPools; $pool++) {
                $poolsName[$stage][$pool] = $sport." ".$stage."-".$pool; // i.e. "Badminton 1-3"
            }
        }
        return $poolsName;
    }

    private function createContenders(){

    }

    private function createGame(){

    }
}