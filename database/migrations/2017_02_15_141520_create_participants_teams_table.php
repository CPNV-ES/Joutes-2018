<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_team', function (Blueprint $table) { 
 
            $table->integer('participant_id')->unsigned(); 
            $table->integer('team_id')->unsigned(); 
            $table->boolean('isCaptain');

            $table->foreign('participant_id')->references('id')->on('participants'); 
            $table->foreign('team_id')->references('id')->on('teams'); 

        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participant_team');
    }
}
