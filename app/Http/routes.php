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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/pages', 'PagesController@showPage');

Route::get('/project/create', 'ProjectsController@create');
Route::get('/projects', 'ProjectsController@index');
Route::get('/project_view/{project}', 'ProjectsController@project_view');
Route::post('/project_view/{project}', 'HoursController@store');


Route::post('/projects', 'ProjectsController@store');
