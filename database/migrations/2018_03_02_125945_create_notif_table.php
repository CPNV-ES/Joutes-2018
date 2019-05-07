<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) { 
            $table->increments('id');
            $table->string('title', 50);
            $table->string('description', 255);
            $table->integer('participant_id')->unsigned();
            $table->boolean('viewed');

            $table->foreign('participant_id')->references('id')->on('participants'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications'); 
    }
}
