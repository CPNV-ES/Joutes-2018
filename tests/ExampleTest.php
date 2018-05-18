<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Tournament;
use App\TournamentSetup;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->testPoolsNames();
    }

    private function testPoolsNames () {
        $setup = new TournamentSetup;
        $tournament = Tournament::find(1);

        $setup->createPools($tournament, 4, 32);
    }
}
