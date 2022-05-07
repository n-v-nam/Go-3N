<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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

        $this->app->bind(
            'App\Services\Contracts\BookTruckInformationServiceInterface',
            'App\Services\BookTruckInformationService',
        );

        $this->app->bind(
            'App\Services\Contracts\DriverServiceInterface',
            'App\Services\DriverService',
        );

        $this->app->bind(
            'App\Services\Contracts\PaymentServiceInterface',
            'App\Services\PaymentService',
        );

        $this->app->bind(
            'App\Services\Contracts\OrderServiceInterface',
            'App\Services\OrderService',
        );

        $this->app->bind(
            'App\Services\Contracts\DashboardServiceInterface',
            'App\Services\DashboardService',
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
