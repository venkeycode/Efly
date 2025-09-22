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
    public const HOME = '/home';
    protected $namespace = 'App\Http\Controllers\Website';
    protected $app_namespace = 'App\Http\Controllers\MobileApp';
    protected $admin_namespace = 'App\Http\Controllers\Admin';
    // protected $company_namespace = 'App\Http\Controllers\Company';
    // protected $companyv2_namespace = 'App\Http\Controllers\Company_Old';
    protected $companyApp_namespace = 'App\Http\Controllers\CompanyApp';
    protected $tech_namespace = 'App\Http\Controllers\Staff';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
                Route::middleware('web')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/web.php'));
                Route::middleware('web')->namespace($this->admin_namespace)
                    ->prefix('admin')
                    ->group(base_path('routes/admin.php'));

                // Route::middleware('web')->namespace($this->company_namespace)
                // ->prefix('company')
                // ->group(base_path('routes/company.php'));

                // Route::middleware('web')->namespace($this->companyv2_namespace)
                // ->prefix('company_v2')
                // ->group(base_path('routes/company_old.php'));


                Route::middleware('api')->namespace($this->app_namespace)
                ->prefix('api')
                ->group(base_path('routes/api.php'));

                Route::middleware('api')->namespace($this->app_namespace)
                ->prefix('api/customer')
                ->group(base_path('routes/api/customer.php'));

                // Route::middleware('api')->namespace($this->companyApp_namespace)
                // ->prefix('api/company')
                // ->group(base_path('routes/api/company.php'));


                Route::middleware('api')->namespace($this->tech_namespace)
                ->prefix('api/staff')
                ->group(base_path('routes/api/staff.php'));
        });
    }
}
