<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\LineIncomeMonthly;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/admin/reports/lines/client';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        Route::bind('client', function ($value) {
            return User::where('role_id', config('constant.ROLE_CLIENT_ID'))->withTrashed()->where("user_id",$value)->firstOrFail();
        });
        Route::bind('operator', function ($value) {
            return User::where('role_id', config('constant.ROLE_OPERATOR_ID'))->withTrashed()->where("user_id",$value)->firstOrFail();
        });
        Route::bind('account_manager', function ($value) {
            return User::where('role_id', config('constant.ROLE_ACCOUNT_MANAGER_ID'))->withTrashed()->where("user_id",$value)->firstOrFail();
        });
        Route::bind('line_income', function ($value) {
            return LineIncomeMonthly::findOrFail($value);
        });

    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
