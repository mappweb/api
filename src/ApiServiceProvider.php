<?php

namespace Mappweb\Api;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{

    private $_packageTag = 'api';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //loads
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Controllers
        //$this->app->make('Mappweb\Api\Http\Controllers\Api\AuthController');
    }


}
