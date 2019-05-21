<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/* Routes who don't need any authentification */
Route::get('/', function () {
    return redirect()->route('events.index');
});
Route::resource('events', 'EventController', ['only' => ['index', 'show']]);
Route::resource('tournaments', 'TournamentController', ['only' => ['index', 'show']]);
Route::resource('events.tournaments', 'EventTournamentController', [ 'only' => ['index', 'show']]);
Route::resource('tournaments.teams', 'TournamentTeamController');
Route::resource('tournaments.pools', 'TournamentPoolController');
Route::resource('admin', 'SessionController', ['only' => ['store', 'destroy']]);
Route::resource('tournaments.schedule', 'ScheduleController', ['only' => ['index']]);
Route::resource('notification', 'NotificationController', ['only' => ['create', 'store']]);
Route::resource('tournamentsBySport', 'TournamentBySportController');
Route::resource('individualRanking', 'individualRankingController');
# Route to download apk
Route::get('/download', function() {
    return view('download.index');
});

/* Routes who need authentification */
// Prefix admin is here to have an url like that : .../admin/tournaments/create
// It will add the "admin" prefix before each "critical" URLs
Route::group(['middleware'=>'checkIsAdmin', 'prefix'=>'admin', 'namespace' => 'Admin'],function(){
	Route::resource('events', 'EventController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
	Route::resource('tournaments', 'TournamentController', ['only' => ['edit', 'update', 'destroy']]);
	Route::resource('events.tournaments', 'EventTournamentController', [ 'only' => ['create', 'store']]);
	Route::resource('sports', 'SportController');
	Route::resource('courts', 'CourtController');
	//Route::resource('teams', 'TeamController');
	//Route::resource('participants', 'ParticipantController');
	//Route::resource('teams.participants', 'TeamParticipantController', ['only' => ['destroy', 'store']]);
});

/* Authorization for Writer or Admin*/
Route::group(['middleware'=>'checkIsWriterOrAdmin', 'prefix'=>'admin', 'namespace' => 'Admin'],function(){
	Route::resource('tournaments.pools.games', 'TournamentPoolGameController', ['only' => 'update']);
	Route::resource('tournaments.pools', 'TournamentPoolController', ['only' => 'update']);
});

/* Authorization for Participant*/
Route::group(['middleware'=>'checkIsParticipant', 'namespace' => 'Profile', 'prefix'=>'participant'],function(){
    Route::resource('profile', 'ProfileController');
    Route::resource('profile.teams', 'ProfileTeamsController');
});

/* Authorization for Participant or Admin*/
Route::group(['middleware'=>'checkIsParticipantOrAdmin', 'namespace' => 'Admin', 'prefix'=>'admin'],function(){
    Route::resource('teams', 'TeamController');
    Route::get('participants/export', 'ParticipantController@export')->name('participants.export');
    Route::resource('participants', 'ParticipantController');
    Route::resource('teams.participants', 'TeamParticipantController', ['only' => ['destroy', 'store']]);
    Route::get('tournaments/{tournament}/export', 'TournamentController@export')->name('tournaments.export');
});