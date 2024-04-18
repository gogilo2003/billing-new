<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use App\Services\MobitechSmsGateway;
use App\Services\SmsGatewayContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SmsGatewayContract::class, function ($app) {
            return new MobitechSmsGateway();
        });

        $helper = app_path('Support/helpers.php');
        if (file_exists($helper)) {
            require_once ($helper);
        }

        if (file_exists(public_path('pdf/header.html')))
            file_put_contents(public_path('pdf/header.html'), view('layouts.header-footer.header')->render());
        if (file_exists(public_path('pdf/footer.html')))
            file_put_contents(public_path('pdf/footer.html'), view('layouts.header-footer.footer')->render());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        //
        Collection::macro('sortByDate', function ($column = 'created_at') {
            /* @var $this Collection */
            return $this->sortBy(function ($datum) use ($column) {
                return strtotime($datum->$column);
            }, SORT_REGULAR, false);
        });

        Collection::macro('sortByDateDesc', function ($column = 'created_at') {
            /* @var $this Collection */
            return $this->sortBy(function ($datum) use ($column) {
                return strtotime($datum->$column);
            }, SORT_REGULAR, true);
        });

        Blade::directive('api_token', function () {
            return Auth::check() ? request()->user()->api_token : null;
        });
    }
}
