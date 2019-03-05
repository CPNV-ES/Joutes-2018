<?php

use Illuminate\Database\Seeder;

class Joutes2019Seeder extends Seeder
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

        $event = \App\Event::where('name', 'like', '%2019%')->first();
        if (!$event)
        {
            $event = new \App\Event();
            $event->name = 'Joutes 2019';
            $event->save();
        }
        $this->eventid = $event->id;

        /*/ make room
        \Illuminate\Support\Facades\DB::statement('delete from games;');
        \Illuminate\Support\Facades\DB::statement('delete from contenders;');
        \Illuminate\Support\Facades\DB::statement('delete from pools;');
        // or not /*/

        $this->basics();
        $this->sports();
        $this->courts();
        $this->tournaments();
        $this->teams();
        $this->BeachVolley();
        $this->Basket();
        $this->UniHockey();
        $this->Badminton();
        $this->Tennis();
    }

    // Common stuff
    private function basics()
    {
        if (!\App\GameType::where('game_type_description', '=', 'Modalités de jeu')->exists())
            (new \App\GameType(['game_type_description' => 'Modalités de jeu']))->save();

        if (!\App\PoolMode::where('mode_description', '=', 'Matches simples')->exists())
            (new \App\PoolMode([
                'mode_description' => 'Matches simples',
                'planningAlgorithm' => '1',
            ]))->save();
        if (!\App\PoolMode::where('mode_description', '=', 'Aller-retour')->exists())
            (new \App\PoolMode([
                'mode_description' => 'Aller-retour',
                'planningAlgorithm' => '2',
            ]))->save();
        if (!\App\PoolMode::where('mode_description', '=', 'Elimination directe')->exists())
            (new \App\PoolMode([
                'mode_description' => 'Elimination directe',
                'planningAlgorithm' => '3',
            ]))->save();
    }

    private function sports()
    {
        foreach (["Badminton" => 32, "Basket" => 16, "Beach" => 12, "Unihockey" => 12, "Tennis" => 32] as $spname => $nbteams)
        {
            if (!\App\Sport::where('name', 'like', "%" . $spname . "%")->exists())
            {
                $s = new \App\Sport();
                $s->name = $spname;
                $s->max_participant = $nbteams;
                $s->save();
            }
        }
    }

    private function courts()
    {
        foreach (["Badminton" => 6, "Basket" => 2, "Beach" => 2, "Unihockey" => 2, "Tennis" => 2] as $spname => $nbcourts)
        {
            if (!\App\Court::where('name', 'like', "%" . $spname . "%")->exists())
            {
                $sp = \App\Sport::where('name', '=', $spname)->first();
                for ($i = 1; $i <= $nbcourts; $i++)
                {
                    $c = new \App\Court();
                    $c->name = $spname . "Court" . $i;
                    $c->sport()->associate($sp);
                    $c->save();
                }
            }
        }
    }

    private function tournaments()
    {
        $event = \App\Event::where('name', 'like', '%2019%')->first();

        foreach (["Badminton" => 32, "Basket" => 16, "Beach" => 12, "Unihockey" => 12, "Tennis" => 32] as $spname => $nbteams)
        {
            if (!\App\Tournament::where('name', 'like', "%" . $spname . "%")->where('start_date', '>=', '2019-06-11')->exists())
            {
                $sp = \App\Sport::where('name', 'like', "%$spname%")->first();
                $t = new \App\Tournament();
                $t->name = "Tournoi de $spname";
                $t->start_date = '2019-06-11';
                $t->event()->associate($event);
                $t->sport()->associate($sp);
                $t->min_teams = $nbteams;
                $t->max_teams = $nbteams;
                $t->save();
            }
        }
    }

    // build teams if necessary
    private function teams()
    {
        $syllables = collect(["le","rat","mus","qué","on","dat","ra","zib","eth","icus","ou","rat","dam","éri","que","est","un","rong","eur","de","la","fa","mil","le","des","cri","cét","idés","de","tren","te","à","qua","ran","te","cm","de","long","qui","pèse","sec","il","est","rép","uté","pou","voir","vi","vre","une","diz","aine","en","cap","ti","vi","té","mais","il","ne","dé","pas","se","que","ra","re","ment","tro","is","ou","qua","tre","ans","dans","la","nat","ure"]);

        foreach (["Bad", "Basket", "Beach", "Unihockey", "Tennis"] as $spname)
        {
            $t = \App\Tournament::where('name', 'like', "%" . $spname . "%")->where('start_date', '>=', '2019-01-01')->first();
            while ($t->teams()->count() < $t->min_teams)
            {
                $team = new \App\Team();
                $tname = "";
                for ($i=0; $i<rand(3,7); $i++) $tname .= $syllables->random();
                $team->name = ucfirst($tname);
                $team->tournament()->associate($t);
                $team->save();
            }
        }
    }

    private function Badminton()
    {
        echo "================================================================================================================\n";
        echo "Badminton\n";
        echo "================================================================================================================\n";
        $bv = \App\Tournament::where('name', 'like', '%Badmin%')->where('start_date', '>=', '2019-01-01')->first();
        if (!$bv)
        {
            echo "Le tournoi de badminton n'existe pas\n";
            return;
        }
        $tournamentid = $bv->id;
        $sportid = $bv->sport_id;

        $teams = \App\Team::where('tournament_id', '=', $tournamentid)->get();

        echo "Tournoi #$tournamentid, " . $teams->count() . " équipes inscrites\n";
        echo "Championnat\nUne seule poule ... ";

        $pool = new \App\Pool([
            'tournament_id' => $tournamentid,
            'start_time' => '09:30',
            'end_time' => '16:00',
            'poolName' => 'The Battle',
            'mode_id' => 1,
            'game_type_id' => 1,
            'poolSize' => 13,
            'stage' => 1,
            'isFinished' => 0
        ]);
        $pool->save();
        $firstpoolStage1 = $pool->id; // we'll need that to put teams into pools

        echo "OK\nInscription des équipes ... ";

        foreach ($teams as $team)
        {
            (new \App\Contender([
                'pool_id' => $firstpoolStage1,
                'team_id' => $team->id
            ]))->save();
        }
        $firstcontender = \App\Contender::where('pool_id', '=', $firstpoolStage1)->first()->id;

        $firstcourt = \App\Court::where('sport_id', '=', $sportid)->first()->id;

        $teams = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12); // Offsets from database id of first contender

        $nbTeams = count($teams);
        $evenNumberOfTeams = ($nbTeams % 2 == 0); // it matters....

        // There will be N-1 rounds (each team will play N-1 games) if the number of teams is even,
        // N rounds if it is odd: each team will play N-1 games and rest during one round
        // Build an array so that it's easy to later define rounds in a richer way than just a number
        $rounds = array();
        for ($i = 0; $i < $nbTeams - 1; $i++) $rounds[] = $i + 1;
        if (!$evenNumberOfTeams) $rounds[] = $i + 1;

        $timeoffset = 0; // in minutes

        echo "OK\nMatches ... ";

        // Ok, let's generate...
        foreach ($rounds as $round)
        {
            $poolHour = 13 + intdiv($timeoffset, 60);
            $poolMinute = $timeoffset % 60;

            $team1Index = 1;
            $team2Index = $evenNumberOfTeams ? $nbTeams - 2 : $nbTeams - 1;
            // "draw the horizontal lines in the polygon", leaving the first team out
            while ($team1Index < $team2Index)
            {
                (new \App\Game(['date' => '2018-07-03', 'start_time' => "$poolHour:$poolMinute", 'contender1_id' => $firstcontender + $teams[$team1Index], 'contender2_id' => $firstcontender + $teams[$team2Index], 'court_id' => $firstcourt]))->save();
                $team1Index++;
                $team2Index--;
            }
            // One extra game for the first and last teams
            if ($evenNumberOfTeams) echo "Game: {$teams[0]} vs {$teams[$nbTeams-1]}<br>";

            // prepare for next round
            $teams = $this->rotate($teams);
            $timeoffset += 15;
        }
        echo "OK\nTerminé.\n\n";

    }

    private function rotate($arr)
        // return the array rotated by one slot. If the number of elements is even, the last item is kept out of the rotation
    {
        $lastIndex = (count($arr) % 2 == 0) ? count($arr) - 2 : count($arr) - 1;
        $first = $arr[0];
        for ($i = 0; $i < $lastIndex; $i++) $arr[$i] = $arr[$i + 1];
        $arr[$lastIndex] = $first;
        return $arr;
    }

    private function UniHockey()
    {
        echo "================================================================================================================\n";
        echo "Unihockey\n";
        echo "================================================================================================================\n";
        $bv = \App\Tournament::where('name', 'like', '%hockey%')->where('start_date', '>=', '2019-01-01')->first();
        if (!$bv)
        {
            echo "Le tournoi de unihockey n'existe pas\n";
            return;
        }
        $tournamentid = $bv->id;
        $sportid = $bv->sport_id;

        $teams = \App\Team::where('tournament_id', '=', $tournamentid)->get();

        echo "Tournoi #$tournamentid, " . $teams->count() . " équipes inscrites\n";
        echo "Championnat\nUne seule poule ... ";

        $pool = new \App\Pool([
            'tournament_id' => $tournamentid,
            'start_time' => '09:30',
            'end_time' => '16:00',
            'poolName' => 'The Championship',
            'mode_id' => 1,
            'game_type_id' => 1,
            'poolSize' => 8,
            'stage' => 1,
            'isFinished' => 0
        ]);
        $pool->save();
        $firstpoolStage1 = $pool->id; // we'll need that to put teams into pools

        echo "OK\nInscription des équipes ... ";

        foreach ($teams as $team)
        {
            (new \App\Contender([
                'pool_id' => $firstpoolStage1,
                'team_id' => $team->id
            ]))->save();
        }
        $firstcontender = \App\Contender::where('pool_id', '=', $firstpoolStage1)->first()->id;

        $firstcourt = \App\Court::where('sport_id', '=', $sportid)->first()->id;

        echo "OK\nMatches ... ";

        // Thank you https://nrich.maths.org/1443
        // Games of pool A
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:30', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:30', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:51', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:51', 'contender1_id' => $firstcontender + 5, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:12', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:12', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:33', 'contender1_id' => $firstcontender + 4, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:33', 'contender1_id' => $firstcontender + 5, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:54', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:54', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:15', 'contender1_id' => $firstcontender + 4, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:15', 'contender1_id' => $firstcontender + 5, 'contender2_id' => $firstcontender + 0, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:00', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:00', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 0, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:21', 'contender1_id' => $firstcontender + 4, 'contender2_id' => $firstcontender + 6, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:21', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 0, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:42', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 6, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:42', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:03', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 6, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:03', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:24', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        echo "OK\nTerminé.\n\n";

    }

    private function Basket()
    {
        echo "================================================================================================================\n";
        echo "Basket\n";
        echo "================================================================================================================\n";
        $bv = \App\Tournament::where('name', 'like', '%Basket%')->where('start_date', '>=', '2019-01-01')->first();
        if (!$bv)
        {
            echo "Le tournoi de basket n'existe pas\n";
            return;
        }
        $tournamentid = $bv->id;
        $sportid = $bv->sport_id;

        $teams = \App\Team::where('tournament_id', '=', $tournamentid)->get();

        echo "Tournoi #$tournamentid, " . $teams->count() . " équipes inscrites\n";
        echo "Championnat\nUne seule poule ... ";

        $pool = new \App\Pool([
            'tournament_id' => $tournamentid,
            'start_time' => '09:30',
            'end_time' => '16:00',
            'poolName' => 'NBA',
            'mode_id' => 1,
            'game_type_id' => 1,
            'poolSize' => 8,
            'stage' => 1,
            'isFinished' => 0
        ]);
        $pool->save();
        $firstpoolStage1 = $pool->id; // we'll need that to put teams into pools

        echo "OK\nInscription des équipes ...";

        foreach ($teams as $team)
        {
            (new \App\Contender([
                'pool_id' => $firstpoolStage1,
                'team_id' => $team->id
            ]))->save();
        }
        $firstcontender = \App\Contender::where('pool_id', '=', $firstpoolStage1)->first()->id;

        $firstcourt = \App\Court::where('sport_id', '=', $sportid)->first()->id;

        echo "OK\nMatches ... ";
        // Thank you https://nrich.maths.org/1443
        // Games of pool A
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:30', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:47', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:04', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:21', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt]))->save();

        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:38', 'contender1_id' => $firstcontender + 5, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:55', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:12', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:29', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt]))->save();

        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:46', 'contender1_id' => $firstcontender + 4, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '12:03', 'contender1_id' => $firstcontender + 5, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '12:20', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '12:37', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt]))->save();

        (new \App\Game(['date' => '2017-06-27', 'start_time' => '12:54', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:11', 'contender1_id' => $firstcontender + 4, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:28', 'contender1_id' => $firstcontender + 5, 'contender2_id' => $firstcontender + 0, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:45', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt + 1]))->save();

        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:02', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:19', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 0, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:36', 'contender1_id' => $firstcontender + 4, 'contender2_id' => $firstcontender + 6, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:53', 'contender1_id' => $firstcontender + 5, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt + 1]))->save();

        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:10', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 0, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:27', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 6, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:44', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '16:01', 'contender1_id' => $firstcontender + 4, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt + 1]))->save();

        (new \App\Game(['date' => '2017-06-27', 'start_time' => '16:18', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 6, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '16:35', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '16:52', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '17:09', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt + 1]))->save();
        echo "OK\nTerminé.\n\n";

    }

    private function Tennis()
    {
        echo "================================================================================================================\n";
        echo "Tennis\n";
        echo "================================================================================================================\n";
        $bv = \App\Tournament::where('name', 'like', '%Tennis%')->where('start_date', '>=', '2019-01-01')->first();
        if (!$bv)
        {
            echo "Le tournoi de tennis n'existe pas\n";
            return;
        }
        $tournamentid = $bv->id;
        $sportid = $bv->sport_id;

        $teams = \App\Team::where('tournament_id', '=', $tournamentid)->get();

        echo "Tournoi #$tournamentid, " . $teams->count() . " équipes inscrites\n";
        echo "Elimination directe\n";

        for ($stage = 1; $stage <= 5; $stage++)
        {
            echo "Tour $stage, ".pow (2,5-$stage)." matches ";
            for ($pooln = 1; $pooln <= pow (2, 5-$stage); $pooln++)
            {
                echo ".";
                $pool = new \App\Pool([
                    'tournament_id' => $tournamentid,
                    'start_time' => '09:30',
                    'end_time' => '16:00',
                    'poolName' => "Game $stage$pooln",
                    'mode_id' => 1,
                    'game_type_id' => 1,
                    'poolSize' => 2,
                    'stage' => $stage,
                    'isFinished' => 0
                ]);
                $pool->save();
                if (!isset($firstpoolStage1)) $firstpoolStage1 = $pool->id; // we'll need that to put teams into pools
            }
            echo "\n";
        }

        echo "OK\nInscription des équipes ... ";

        $pooln = 0;
        foreach ($teams as $team)
        {
            $contender = new \App\Contender([
                'pool_id' => $firstpoolStage1+intdiv($pooln++,2),
                'team_id' => $team->id
            ]);
            $contender->save();
            if (!isset($firstcontender)) $firstcontender = $contender->id; // we'll need that to put teams into pools
        }

        echo "OK\nLiens entre poules ... ";
        $nbpoolstoadd = 8;
        $pooldelta = 16;
        $poolfromdelta = 0;

        while ($nbpoolstoadd > 0)
        {
            for ($pooln = 0; $pooln < $nbpoolstoadd; $pooln++)
            {
                $contender = new \App\Contender([
                    'pool_id' => $firstpoolStage1+$pooldelta+$pooln,
                    'pool_from_id' => $firstpoolStage1+$poolfromdelta+$pooln*2,
                    'rank_in_pool' => 1
                ]);
                $contender->save();
                $contender = new \App\Contender([
                    'pool_id' => $firstpoolStage1+$pooldelta+$pooln,
                    'pool_from_id' => $firstpoolStage1+$poolfromdelta+$pooln*2+1,
                    'rank_in_pool' => 1
                ]);
                $contender->save();
            }
            $poolfromdelta = $pooldelta;
            $pooldelta += $nbpoolstoadd;
            $nbpoolstoadd = intdiv($nbpoolstoadd,2);
        }

        $firstcourt = \App\Court::where('sport_id', '=', $sportid)->first()->id;

        echo "OK\nMatches ... ";

        for ($matchnumber = 0; $matchnumber < 31; $matchnumber++)
            (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:30', 'contender1_id' => $firstcontender+$matchnumber*2, 'contender2_id' => $firstcontender+$matchnumber*2 + 1, 'court_id' => $firstcourt]))->save();

        echo "OK\nTerminé.\n\n";

    }

    private function BeachVolley()
    {
        echo "================================================================================================================\n";
        echo "Beach Volley\n";
        echo "================================================================================================================\n";
        $bv = \App\Tournament::where('name', 'like', '%Beach%')->where('start_date', '>=', '2019-01-01')->first();
        if (!$bv)
        {
            echo "Le tournoi de beach n'existe pas\n";
            return;
        }
        $tournamentid = $bv->id;
        $sportid = $bv->sport_id;

        $teams = \App\Team::where('tournament_id', '=', $tournamentid)->get();

        echo "Tournoi #$tournamentid, " . $teams->count() . " équipes inscrites\n";
        echo "Stage 1 = 2 poules de 6 équipes ... ";

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
        $firstpoolStage1 = $pool->id; // we'll need that to put teams into pools

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

        echo "OK\nInscription des équipes ";
        $nbTeams = 0;
        foreach ($teams as $team)
        {
            (new \App\Contender([
                'pool_id' => $firstpoolStage1 + intdiv($nbTeams, 6),
                'team_id' => $team->id
            ]))->save();
            $nbTeams++;
            echo ".";
        }
        $firstcontender = \App\Contender::where('pool_id', '=', $firstpoolStage1)->first()->id;
        $firstcourt = \App\Court::where('sport_id', '=', $sportid)->first()->id;

        echo " OK\nMatches de la poule A ... ";
        // Games of pool A
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:30', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:40', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:50', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:00', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:10', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:20', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:30', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:40', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:50', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:00', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:10', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:20', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:30', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:40', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:50', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();

        echo " OK\nMatches de la poule B ... ";
        // Games of pool B
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:30', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:40', 'contender1_id' => $firstcontender + 8, 'contender2_id' => $firstcontender + 9, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '09:50', 'contender1_id' => $firstcontender + 9, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:00', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 8, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:10', 'contender1_id' => $firstcontender + 9, 'contender2_id' => $firstcontender + 10, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:20', 'contender1_id' => $firstcontender + 7, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:30', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 9, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:40', 'contender1_id' => $firstcontender + 7, 'contender2_id' => $firstcontender + 6, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '10:50', 'contender1_id' => $firstcontender + 8, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:00', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 10, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:10', 'contender1_id' => $firstcontender + 7, 'contender2_id' => $firstcontender + 8, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:20', 'contender1_id' => $firstcontender + 9, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:30', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:40', 'contender1_id' => $firstcontender + 7, 'contender2_id' => $firstcontender + 9, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '11:50', 'contender1_id' => $firstcontender + 8, 'contender2_id' => $firstcontender + 10, 'court_id' => $firstcourt + 1]))->save();

        echo "OK\nStage 2 = 2 poules de 6 équipes\n";

        $pool = new \App\Pool([
            'tournament_id' => $tournamentid,
            'start_time' => '09:30',
            'end_time' => '11:45',
            'poolName' => 'Winners',
            'mode_id' => 1,
            'game_type_id' => 1,
            'poolSize' => 6,
            'stage' => 2,
            'isFinished' => 0
        ]);
        $pool->save();
        $firstpoolStage2 = $pool->id; // we'll need that to put teams into pools

        (new \App\Pool([
            'tournament_id' => $tournamentid,
            'start_time' => '09:30',
            'end_time' => '11:45',
            'poolName' => 'Cool',
            'mode_id' => 1,
            'game_type_id' => 1,
            'poolSize' => 6,
            'stage' => 2,
            'isFinished' => 0
        ]))->save();

        echo "Placement des équipes ... ";
        (new \App\Contender(['pool_id' => $firstpoolStage2, 'pool_from_id' => $firstpoolStage1, 'rank_in_pool' => 1]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2, 'pool_from_id' => $firstpoolStage1, 'rank_in_pool' => 2]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2, 'pool_from_id' => $firstpoolStage1, 'rank_in_pool' => 3]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2, 'pool_from_id' => $firstpoolStage1 + 1, 'rank_in_pool' => 1]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2, 'pool_from_id' => $firstpoolStage1 + 1, 'rank_in_pool' => 2]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2, 'pool_from_id' => $firstpoolStage1 + 1, 'rank_in_pool' => 3]))->save();

        (new \App\Contender(['pool_id' => $firstpoolStage2 + 1, 'pool_from_id' => $firstpoolStage1, 'rank_in_pool' => 4]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2 + 1, 'pool_from_id' => $firstpoolStage1, 'rank_in_pool' => 5]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2 + 1, 'pool_from_id' => $firstpoolStage1, 'rank_in_pool' => 6]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2 + 1, 'pool_from_id' => $firstpoolStage1 + 1, 'rank_in_pool' => 4]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2 + 1, 'pool_from_id' => $firstpoolStage1 + 1, 'rank_in_pool' => 5]))->save();
        (new \App\Contender(['pool_id' => $firstpoolStage2 + 1, 'pool_from_id' => $firstpoolStage1 + 1, 'rank_in_pool' => 6]))->save();

        $firstcontender = \App\Contender::where('pool_id', '=', $firstpoolStage2)->first()->id;
        $firstcourt = \App\Court::where('sport_id', '=', $sportid)->first()->id;

        echo " OK\nMatches de la poule Winner ... ";
        // Games of pool Winner
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:00', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 1, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:10', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:20', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:30', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:40', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:50', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:00', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:10', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:20', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:30', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:40', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 2, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:50', 'contender1_id' => $firstcontender + 3, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:00', 'contender1_id' => $firstcontender + 0, 'contender2_id' => $firstcontender + 5, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:10', 'contender1_id' => $firstcontender + 1, 'contender2_id' => $firstcontender + 3, 'court_id' => $firstcourt]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:20', 'contender1_id' => $firstcontender + 2, 'contender2_id' => $firstcontender + 4, 'court_id' => $firstcourt]))->save();

        echo " OK\nMatches de la poule Loser ... ";
        // Games of pool Losers
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:00', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 7, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:10', 'contender1_id' => $firstcontender + 8, 'contender2_id' => $firstcontender + 9, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:20', 'contender1_id' => $firstcontender + 9, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:30', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 8, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:40', 'contender1_id' => $firstcontender + 9, 'contender2_id' => $firstcontender + 10, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '13:50', 'contender1_id' => $firstcontender + 7, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:00', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 9, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:10', 'contender1_id' => $firstcontender + 7, 'contender2_id' => $firstcontender + 6, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:20', 'contender1_id' => $firstcontender + 8, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:30', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 10, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:40', 'contender1_id' => $firstcontender + 7, 'contender2_id' => $firstcontender + 8, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '14:50', 'contender1_id' => $firstcontender + 9, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:00', 'contender1_id' => $firstcontender + 6, 'contender2_id' => $firstcontender + 11, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:10', 'contender1_id' => $firstcontender + 7, 'contender2_id' => $firstcontender + 9, 'court_id' => $firstcourt + 1]))->save();
        (new \App\Game(['date' => '2017-06-27', 'start_time' => '15:20', 'contender1_id' => $firstcontender + 8, 'contender2_id' => $firstcontender + 10, 'court_id' => $firstcourt + 1]))->save();

        echo " OK\nTerminé.\n\n";

    }
}
