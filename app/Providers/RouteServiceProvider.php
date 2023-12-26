<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

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
                ->group(base_path('routes/api.php'))
                ->group(base_path('routes/modules/catalog.php'))
                ->group(base_path('routes/modules/shop_cart.php'))
                ->group(base_path('routes/modules/users.php'))
                ->group(base_path('routes/modules/vacancies.php'))
                ->group(base_path('routes/modules/checkout.php'))
                ->group(base_path('routes/modules/payments.php'))
                ->group(base_path('routes/modules/delivery.php'))
                ->group(base_path('routes/modules/live.php'))
                ->group(base_path('routes/modules/orders.php'))
                ->group(base_path('routes/modules/supports.php'))
                ->group(base_path('routes/modules/questionnaire.php'))
                ->group(base_path('routes/modules/promotions.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
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
            return Limit::perMinute(600)->by($request->user()?->id ?: $request->ip());
        });
    }
}
