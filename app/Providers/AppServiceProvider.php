<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;
use App\Models\WebsiteSetting;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // Paginator::useBootstrapFive();
        // Paginator::useBootstrapFour();
        // View::composer('*', function ($view) {
        //     $uriSlug = 0;

        //     if (isset (\Request::route()->uri)) {
        //         $uriSlug = \Request::route()->uri;
        //     } else {
        //         $uriSlug = 0;
        //     }
        //     // dd($uriSlug);

        //         $site = SiteSetting::where('id', 1)->first();
        //         $view->with(['site' => $site, 'uriSlug' => $uriSlug]);


        // });
    }
}
