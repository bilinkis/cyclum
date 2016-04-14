<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'pagesController@index')->middleware(['guest']);

Route::get('/user', 'pagesController@indexUsers')->middleware(['guest']);

Route::get('/consultant', 'pagesController@indexConsultants')->middleware(['guest']);

Route::get('/startup', 'pagesController@indexStartups')->middleware(['guest']);

Route::get('home', 'pagesController@home')->middleware(['auth']);

Route::get('testapi', function(){
    return view('pages/testapi');
});

Route::get('trello', function(){
    return view('pages/trello');
});

Route::get('validateworker/{id}', 'pagesController@validateWorker')->middleware(['guest']);

Route::get('stats/{cryptedId}', 'pagesController@stats')->middleware(['auth']);
Route::get('projectstats', 'pagesController@projectStats')->middleware(['auth']);

// Authentication routes...
Route::post('auth/login', 'Auth\AuthController@postLogin')->middleware(['guest']);
Route::get('auth/logout', 'Auth\AuthController@getLogout')->middleware(['auth']);

// Registration routes...
Route::post('auth/register', 'Auth\AuthController@postRegister')->middleware(['guest']);

Route::get('/setcookie', 'pagesController@setCookie');

Route::get('/setvisittime', 'pagesController@setVisitTime');

Route::post('/addtask', 'pagesController@addTask')->middleware(['leader']);

Route::post('/addworker', 'pagesController@addWorker')->middleware(['leader']);

Route::get('/deletetask', 'pagesController@deleteTask')->middleware(['leader']);

Route::post('/edittask', 'pagesController@editTask')->middleware(['auth']);

Route::get('/changetask', 'pagesController@changeTask')->middleware(['auth']);

Route::get('/savestats', 'pagesController@saveStats');

Route::get('/stats/{encryptedId}/validate/{decition}', 'pagesController@validateChange')->middleware(['leader']);

Route::get('/account', 'pagesController@account')->middleware(['auth']);

Route::post('/passconfirmation', 'pagesController@passConfirmation');

Route::get('/gettaskworkers', 'pagesController@getTaskWorkers')->middleware(['leader']);

Route::post('/addworkertask', 'pagesController@addWorkerTask')->middleware(['leader']);

Route::get('/validateleader/{id}', 'pagesController@validateLeader');

Route::post('/edituser', 'pagesController@editUser')->middleware(['auth']);

Route::get('/savestatseachweek', 'pagesController@saveStatsEachWeek');

Route::get('/poop', 'pagesController@poop');

Route::get('/deleteworker/{encryptedId}', 'pagesController@deleteWorker');

Route::get('/upgrade/{encryptedId}', 'pagesController@upgrade');

Route::get('/downgrade/{encryptedId}', 'pagesController@downgrade');

Route::post('/addteam', 'pagesController@addTeam');

Route::get('/changeteamtasks', 'pagesController@changeTeamTasks');

Route::get('/changeteams', 'pagesController@changeTeams');