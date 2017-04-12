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

        view()->composer(['users.index','home', 'project.index'], function ($view) {
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
