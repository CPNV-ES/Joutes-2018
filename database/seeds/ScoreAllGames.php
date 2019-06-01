<?php

use Illuminate\Database\Seeder;

class ScoreAllGames extends Seeder
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
        $games = \App\Game::all();
        foreach ($games as $game)
        {
            $game->score_contender1 = rand(3,10);
            $game->score_contender2 = rand(3,10);
            $game->save();
        }
    }

}
