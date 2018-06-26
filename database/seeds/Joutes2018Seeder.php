<?php

use Illuminate\Database\Seeder;

class Joutes2018Seeder extends Seeder
{
    private $eventid;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $db = \Config::get('database.connections.mysql.database');
        $user = \Config::get('database.connections.mysql.username');
        $pass = \Config::get('database.connections.mysql.password');

        $event = \App\Event::where('name', 'like', '%2018%')->first();
        if (!$event) die ("L'événement n'existe pas\n");
        $this->eventid = $event->id;

        $this->basics();
        $this->BeachVolley();
    }

    // Common stuff
    private function basics()
    {
        (new \App\GameType(['game_type_description' => 'Modalités de jeu']))->save();

        (new \App\Poolmode([
            'modeDescription' => 'Matches simples',
            'planningAlgorithm' => '1',
        ]))->save();
        (new \App\Poolmode([
            'modeDescription' => 'Aller-retour',
            'planningAlgorithm' => '2',
        ]))->save();
        (new \App\Poolmode([
            'modeDescription' => 'Elimination directe',
            'planningAlgorithm' => '3',
        ]))->save();

        $event = (new \App\Event([
            'name' => 'Joutes 2018',
            'img' => '2017_06_25-19_35_57.png'
        ]));
        $event->save();
        $this->eventid = $event->id;

        // Temporarily for ease of testing: create admin and writer
        (new \App\User([
            'username' => 'writer',
            'password' => '$2y$10$1nlzftBwvtxq6yueKHvROukJ9acntgG1pmu.qb1UY80pJWFchadP6',
            'role' => 'administrator'
        ]))->save();
        (new \App\User([
            'username' => 'admin',
            'password' => '$2y$10$RsiBblUoNfis0/TAmWR3NuLQRUITxQbQmsaSAdCPyto1z4eUs4ZlW',
            'role' => 'writter'
        ]))->save();
    }

    private function BeachVolley()
    {
        echo "Beach Volley\n";
        $bv = \App\Tournament::where('name', 'like', '%Beach%')->first();
        if (!$bv) die ("Le tournoi n'existe pas\n");
        $tournamentid = $bv->id;
        $sportid = $bv->sport_id;

        $teams = \App\Team::where('tournament_id', '=', $tournamentid)->get();

        echo "Tournoi #$tournamentid, " . $teams->count() . " équipes inscrites\n";
        echo "Stage 1 = 2 poules de 6 équipes\n";

        $pool = new \App\Pool([
            'tournament_id' => $tournamentid,
            'start_time' => '09:30',
            'end_time' => '11:45',
            'poolName' => 'A',
            'mode_id' => 1,
            'game_type_id' => 1,
            'poolSize' => 6,
            'stage' => 1,
            'isFinished' => 0
        ]);
        $pool->save();
        $firstpool = $pool->id; // we'll need that to put teams into pools

        (new \App\Pool([
            'tournament_id' => $tournamentid,
            'start_time' => '09:30',
            'end_time' => '11:45',
            'poolName' => 'B',
            'mode_id' => 1,
            'game_type_id' => 1,
            'poolSize' => 6,
            'stage' => 1,
            'isFinished' => 0
        ]))->save();

        $offset = 0;
        foreach ($teams as $team)
        {
            (new \App\Contender([
                'pool_id' => $firstpool + $offset,
                'team_id' => $team->id
            ]))->save();
            $offset = ($offset+1) % 2;
        }
        $firstcontender = \App\Contender::where('pool_id', '=', $firstpool)->first()->id;
        $firstcourt = \App\Court::where('sport_id', '=', $sportid)->first()->id;

        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:30', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:30', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:15', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:15', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:00', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 0, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:00', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:45', 'contender1_id' => $firstcontender + 4, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:45', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:30', 'contender1_id' => $firstcontender + 4, 'contender2_id' => $firstcontender + 6, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:30', 'contender1_id' => $firstcontender + 5, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:15', 'contender1_id' => $firstcontender + 7, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:15', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:00', 'contender1_id' => $firstcontender + 8, 'contender2_id' => $firstcontender + 9, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:00', 'contender1_id' => $firstcontender + 10, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:45', 'contender1_id' => $firstcontender + 8, 'contender2_id' => $firstcontender + 10, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:45', 'contender1_id' => $firstcontender + 9, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:30', 'contender1_id' => $firstcontender + 11, 'contender2_id' => $firstcontender + 8, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:30', 'contender1_id' => $firstcontender + 10, 'contender2_id' => $firstcontender + 9, 'court_id' => $firstcourt + 1]))->save();

    }
}
