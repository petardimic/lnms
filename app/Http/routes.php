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

Route::get('/', 'PagesController@home');

Route::get('nodes/{id}/test', 'NodesController@test');
Route::get('nodes/{id}/discover', 'NodesController@discover');
Route::patch('nodes/{id}/discover', 'NodesController@discover_update');
Route::resource('nodes', 'NodesController');

// api
Route::get('api/v1/nodes/{id}/ping', 'NodesController@ping');
Route::get('api/v1/nodes/{id}/snmp', 'NodesController@snmp');

