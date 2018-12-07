<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['middleware'=>App\Http\Middleware\AddCorsHeaders::class], function($api) {

        $api->resource('events', 'App\Http\Controllers\EventController', [ 'only' => [
            'index', 'show'
        ]]);

        $api->resource('events.tournaments', 'App\Http\Controllers\EventTournamentController', [ 'only' => [
            'index', 'show'
        ]]);

        $api->resource('events.tournaments.pools', 'App\Http\Controllers\API\EventTournamentPoolController', [ 'only' => [
            'index', 'show'
        ]]);

        $api->resource('events.teams', 'App\Http\Controllers\API\EventTeamController', [ 'only' => [
            'index', 'show'
        ]]);

        $api->resource('events.participants', 'App\Http\Controllers\API\EventParticipantController', [ 'only' => [
            'index', 'show'
        ]]);

        $api->resource('participants.notifications', 'App\Http\Controllers\API\ParticipantNotificationController', [ 'only' => [
            'index'
        ]]);
        
        $api->resource('notifications', 'App\Http\Controllers\NotificationController', [ 'only' => [
            'update'
        ]]);
        
        $api->resource('tournaments.schedule', 'App\Http\Controllers\API\ScheduleController', [ 'only' => [
            'index'
        ]]);

       $api->resource('profil', 'App\Http\Controllers\API\ProfilController', [ 'only' => [
           'index'
       ]]);
    });
});
