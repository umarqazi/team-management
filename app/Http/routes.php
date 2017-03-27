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

Route::get('/', 'HomeController@index' );

Route::get('/pages', 'PagesController@showPage');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('users', 'UsersController');

Route::get('/project/create', 'ProjectsController@create');

Route::get('/projects', 'ProjectsController@index');

Route::get('/project/{project}', 'ProjectsController@show');

Route::post('/project/{project}', 'HoursController@store');

Route::post('/projects', 'ProjectsController@store');

Route::get('/project/{project}/edit', 'ProjectsController@edit');

Route::put('/projects/{project}', 'ProjectsController@update');

Route::delete('/projects/{project}', 'ProjectsController@destroy');

