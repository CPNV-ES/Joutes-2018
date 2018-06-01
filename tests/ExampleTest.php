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
        $this->testPools();
    }

    private function testPools() {
        $setup = new TournamentSetup;
        $tournament = Tournament::find(1);

        $setup->createContenders($tournament);

        //$setup->createPools($tournament,6,30,4);
    }
}
