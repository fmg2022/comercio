<?php

namespace App\Providers;

use App\Models\OrderProduct;
use App\Observers\OrderProductObserver;
use Illuminate\Support\ServiceProvider;

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
        OrderProduct::observe((OrderProductObserver::class));
    }
}
