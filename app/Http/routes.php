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

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('home', 'PagesController@home');
Route::get('/', 'PagesController@home');

Route::get('nodes/{id}/test',       'NodesController@test');
Route::get('nodes/{id}/discover',   'NodesController@discover');
Route::get('nodes/{id}/ports',      'NodesController@ports');
Route::get('nodes/{id}/graph_ping', 'NodesController@graph_ping');
Route::get('nodes/{id}/graph_snmp', 'NodesController@graph_snmp');
Route::patch('nodes/{id}/discover', 'NodesController@discover_update');
Route::patch('nodes/{id}/ports',    'NodesController@ports_update');
Route::resource('nodes', 'NodesController');

Route::get('ports/{id}', 'PortsController@show');
Route::get('ports/{id}/pollings', 'PortsController@pollings');
Route::patch('ports/{id}/pollings', 'PortsController@pollings_update');

Route::resource('locations', 'LocationsController');

// api
Route::get('api/v1/nodes/{id}/ping', 'NodesController@ping');
Route::get('api/v1/nodes/{id}/snmp', 'NodesController@snmp');

