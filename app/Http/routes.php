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

Route::get('/pages', 'PagesController@showPage');

Route::auth();

Route::group( ['middleware'  => 'auth'], function(){

    Route::group(['middleware'  => 'role:admin'], function(){

        Route::resource( 'users', 'UsersController' );

        Route::resource( 'roles', 'RolesController' );
    });

    Route::get( 'home', 'HomeController@index' );

    Route::get( '/', 'HomeController@index' );

    Route::group(['middleware'  => 'permission:create project'], function(){

        Route::get( 'projects/create', 'ProjectsController@create' );
        Route::post( 'projects', 'ProjectsController@store' );
    });
    Route::group(['middleware'  => 'permission:edit project'], function(){

        Route::get( 'projects/{project}/edit', 'ProjectsController@edit' );
        Route::post( 'projects/{id}', 'ProjectsController@update' );
    });
    Route::group(['middleware'  => 'permission:delete project'], function(){

        Route::post( 'projects/{id}', 'ProjectsController@destroy' );
    });

    Route::resource('projects', 'ProjectsController', ['only' => ['index', 'show']]);
//    Route::get('/projects', 'ProjectsController@index');

//    Route::get('/project/create', 'ProjectsController@create');

//    Route::get('/project/{project}', 'ProjectsController@show');

    Route::post('/hour', 'HoursController@store');
    Route::post('/hour/update/{id}', 'HoursController@update');
    Route::post('/hour/delete/{id}', 'HoursController@delete');

//    Route::post('/projects', 'ProjectsController@store');

//    Route::get('/project/{project}/edit', 'ProjectsController@edit');

//    Route::put('/projects/{project}', 'ProjectsController@update');

//    Route::delete('/projects/{project}', 'ProjectsController@destroy');

//    ********************************************************************
    Route::get('/hour/{project}/{month}', 'HoursController@show');
});

