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


Route::get('/totalankle', function(){
    return File::get(public_path(). '/totalankle/index.php');
});

Route::get('/camp', function(){
   return File::get(public_path(). '/camp/index.php');
});

Route::get('/4hq-israel-encounters', function(){
   return File::get(public_path(). '/4hq-israel-encounters/index.php');
});

Route::get('/hansa-garten', function(){
   return File::get(public_path(). '/hansa-garten/index.php');
});

Route::get('/landing-grad', function(){
   return File::get(public_path(). '/landing-grad/index.php');
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

    // Route Group for Tasks

    Route::group(['middleware' => ['task:admin, pm']], function (){
        // Resource Routes For Tasks
        Route::resource( 'tasks', 'TasksController', ['except' => ['index', 'show']]);

        //  Resource Routes For Sub-tasks
        Route::resource( 'subtasks', 'SubtasksController', ['except' => ['index', 'show']]);

        // Exhausted Resource Routes for Tasks
        // Route To Fetch Users of Specific Project
        Route::get('/tasks/users/{ProjectID}', [
            'uses' => 'TasksController@showProjectUsers',
            'as' => 'ProjectUsers'
        ]);

        Route::get('/tasks/project/{projectId?}', [
            'uses' => 'TasksController@showProject',
            'as' => 'Project'
        ]);

        // Reopen and Change Request For Tasks
        Route::post('/reopen', [
           'uses' => 'TasksController@reopenAndChangeRequest',
           'as' => 'reopenTask'
        ]);

        // Route for Assign Task
        Route::get('/assign_task', [
            'uses' => 'TasksController@assignTask',
            'as' => 'assignTask'
        ]);

        Route::post('/reopen_subtask', [
           'uses' => 'SubtasksController@reopenAndChangeRequest',
           'as' => 'assignSubtask'
        ]);
    });

    Route::resource( 'tasks', 'TasksController', ['only' => ['index', 'show']]);
    // Exhausted Resource Routes for Tasks
    Route::get('/tasks/specific/{pid}', [
        'uses' => 'TasksController@showProjectSpecific',
        'as' => 'specificView'
    ]);

    Route::get('/status',[
       'uses' => 'TasksController@updateStatus',
       'as' => 'updateStatus'
    ]);

    Route::resource( 'subtasks', 'SubtasksController', ['only' => ['index', 'show']]);

    Route::get('/subtask_status',[
        'uses' => 'SubtasksController@updateStatus',
        'as' => 'updateStatus'
    ]);

    // Route Group for Sub Tasks
    /*Route::group(['middleware' => ['subtask:admin, pm']], function (){
        Route::resource( 'subtasks', 'SubtasksController', ['except' => ['index', 'show']]);
    });

    Route::resource( 'subtasks', 'SubtasksController', ['only' => ['index', 'show']]);*/


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

    Route::group(['middleware'  => ['project:admin,pm,projectlead,frontend,sales']], function(){

        Route::resource('projects', 'ProjectsController', ['only' => ['index', 'show']]);

        // Routes Added By Umar Farooq
        Route::get('/projectView', [
           'uses' => 'ProjectsController@getMainView',
            'as' => 'mainProjectView'
        ]);
    });
    Route::get('/downloadExcel_project_by_months/{id}/{type}', 'ProjectsController@downloadExcel');
//    Route::get('/projects', 'ProjectsController@index');

//    Route::get('/project/create', 'ProjectsController@create');

//    Route::get('/project/{project}', 'ProjectsController@show');

    Route::post('/hour', 'HoursController@store');

    // Developer Task Estimation Route
    Route::post('/developerTaskEstimation','HoursController@storeDeveloperTaskEstimation');
    // Developer Subtask Estimation Route
    Route::post('/developerSubtaskEstimation','HoursController@storeDeveloperSubtaskEstimation');

    // Developer Task Consumption Route
    Route::post('/task_consumed_hours','HoursController@storeTaskConsumption');
    // Developer Subtask Consumption Route
    Route::post('/subtask_consumed_hours','HoursController@storeSubtaskConsumption');

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

