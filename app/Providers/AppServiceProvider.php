<?php

namespace App\Providers;

use App\User;
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

        if(!app()->runningInConsole())
        {
            $this->resources['allocated']   = User::allocatedUsers();
            $this->resources['free']        = User::freeUsers();

            view()->composer(['users.index','home', 'project.index'], function ($view) {
                $view->with('resources', $this->resources);
            });
        }

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
