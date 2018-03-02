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
Route::get('/verge', function(){
    return File::get(public_path(). '/verge/index.php');
});

Route::get('/pages', 'PagesController@showPage');

Route::auth();

Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
Route::get('/callback/{provider}', 'SocialAuthController@callback');

Route::group(['middleware'  => 'auth'], function(){

    Route::group(['middleware'  => ['role:admin, hr']], function(){

        Route::resource( 'users', 'UsersController', ['except' => ['edit', 'update']] );

        Route::resource( 'roles', 'RolesController' );
    });

    Route::group(['middleware' => ['profile:admin,HR']], function(){
        Route::resource( 'users', 'UsersController', ['only' => ['edit', 'update']] );
    });

    Route::get( 'home', 'HomeController@index' );

    Route::get( '/', 'HomeController@index' );
    Route::get( '/home/{id}/{proj_month}/{resource?}', 'HomeController@getHours' );

    Route::group(['middleware'  => 'permission:create project'], function(){

        Route::get( 'projects/create', 'ProjectsController@create' );
        Route::post( 'projects', 'ProjectsController@store' );
    });
    Route::group(['middleware'  => 'permission:edit project'], function(){

        Route::get( 'projects/{project}/edit', 'ProjectsController@edit' );
        Route::put( 'projects/{id}', 'ProjectsController@update' );
    });
    Route::group(['middleware'  => 'permission:delete project'], function(){

        Route::delete( 'projects/{id}', 'ProjectsController@destroy' );
    });
    // Purpose of this Middleware ??????

    // Opposite
    Route::group(['middleware'  => ['project:admin,pm,projectlead,frontend,sales']], function(){

        // Why middleware on Projects Route ????
        Route::resource('projects', 'ProjectsController', ['only' => ['index', 'show']]);

        // Routes Added By Umar Farooq
        Route::get('/projectView', [
           'uses' => 'ProjectsController@getMainView',
            'as' => 'mainProjectView'
        ]);
        Route::get('/taskDetailView', [
           'uses' => 'ProjectsController@getDetailView',
           'as' => 'taskDetailView'
        ]);
    });
    Route::get('/downloadExcel_project_by_months/{id}/{type}', 'ProjectsController@downloadExcel');
//    Route::get('/projects', 'ProjectsController@index');

//    Route::get('/project/create', 'ProjectsController@create');

//    Route::get('/project/{project}', 'ProjectsController@show');
    Route::get('/hour/{id}', 'HoursController@updateStatus');

    Route::post('/hour', 'HoursController@store');
    Route::post('/hour/update/{id}', 'HoursController@update');
    Route::post('/hour/delete/{id}', 'HoursController@delete');
    Route::get('/downloadExcel_hour_by_months/{project}/{year_month}/', 'HoursController@downloadExcel');
    Route::post('/downloadExcel_hour_by_filter/{project}/', 'HoursController@downloadExcelfilter');

//    Route::post('/projects', 'ProjectsController@store');

//    Route::get('/project/{project}/edit', 'ProjectsController@edit');

//    Route::put('/projects/{project}', 'ProjectsController@update');

//    Route::delete('/projects/{project}', 'ProjectsController@destroy');

//    ********************************************************************
    Route::get('/hour/{project}/{year_month}', 'HoursController@show');

    //Fetch All Project Names of Projects
    Route::get('/projectKey', 'ProjectsKeyController@getProjectNames');
});

