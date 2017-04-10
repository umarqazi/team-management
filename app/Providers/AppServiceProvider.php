<?php

namespace App\Providers;

use App\User;
use App\Project;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public  $resources;
    public function boot()
    {
        // Using Closure based composers...

//        view()->composer('layouts.app', function ($view) {
//            $view->with('resources', User::with(['projects', function($p){
//                $p->select('count(*) as active');
//            }])->whereHas('projects', function($p){
//                $p->where('status', '=', 1);
//            })->havingRaw('active > 0')->get());
//        });

        $this->resources['allocated']  = User::whereHas('roles', function($r){
            $r->whereIn('name', array('teamlead', 'developer'));
        })->whereHas('projects', function($p){
            $p->selectRaw('count(*) AS active')->where('status', 1)->havingRaw('active >= 1');
        })->get();
        $this->resources['free']      = User::whereHas('roles', function($r){
            $r->whereIn('name', array('teamlead', 'developer'));
        })->whereHas('projects', function($p){
            $p->selectRaw('count(*) AS active')->where('status', 1)->havingRaw('active = 0');
        })->get();

//        foreach($resources['allocated'] as $user) {
//                var_dump($user->name);
//            echo "<br>";
//        }
//        foreach($resources['free'] as $user) {
//            var_dump($user->name);
//
//            echo "<br>";
//        }
//        echo "</pre>";
//        die();

        view()->composer(['users.index','home'], function ($view) {
            $view->with('resources', $this->resources);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
