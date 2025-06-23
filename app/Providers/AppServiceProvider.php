<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Helpers\Helper;
use Illuminate\Pagination\Paginator;
use App\Observers\LineObserver;
use App\Models\Line;
use App\Services\LineOperatorService;
use App\Services\LineClientService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('helper', function ($app) {
            return new Helper();
        });
        $this->app->singleton('line_operator_service', function ($app) {
            return new LineOperatorService;
        });
        $this->app->singleton('line_client_service', function ($app) {
            return new LineClientService;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Line::observe(LineObserver::class);
    }
}
