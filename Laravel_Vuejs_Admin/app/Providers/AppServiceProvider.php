<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Services\Contracts\UserServiceInterface',
            'App\Services\UserService',
        );

        $this->app->bind(
            'App\Services\Contracts\TruckServiceInterface',
            'App\Services\TruckService',
        );

        $this->app->bind(
            'App\Services\Contracts\PostServiceInterface',
            'App\Services\PostService',
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
