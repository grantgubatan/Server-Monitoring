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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'HomeController@profile');

Route::get('/status', 'HomeController@status');

Route::get('/servers', 'HomeController@servers');

Route::get('/admins', 'HomeController@admins');

Route::get('/help', 'HomeController@help');

Route::post('/add-server', 'HomeController@addServer');

Route::post('/home', 'HomeController@pingSearch');

Route::post('/add-account', 'HomeController@addAccount');

Route::put('/servers', 'HomeController@updateServers');

Route::put('/profile', 'HomeController@editProfile');

Route::put('/edit-server/{id}', 'HomeController@updateServer');

Route::put('/ping-server/{id}', 'HomeController@pingServer');

Route::put('/change-rights/{id}', 'HomeController@changeRights');

Route::delete('/delete-server/{id}', 'HomeController@deleteServer');

Route::delete('/delete-account/{id}', 'HomeController@deleteAccount');
