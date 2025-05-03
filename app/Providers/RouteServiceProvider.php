<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';
    public const SUPERADMIN_HOME = '/superadmin/dashboard';
    public const ADMIN_HOME = '/admin/dashboard';
    public const CUSTOMER_HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // API Routes
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            // Web Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // Auth Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/auth.php'));

            // Customer Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/pelanggan.php'));

            // Superadmin Routes
            Route::middleware(['web', 'auth.session:superadmin'])
                ->namespace($this->namespace)
                ->group(base_path('routes/superadmin.php'));

            // Settings Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/settings.php'));
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