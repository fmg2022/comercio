<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Category;
use App\Models\OrderProduct;
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
        OrderProduct::observe(\App\Observers\OrderProductObserver::class);
        Category::observe(\App\Observers\CategoryObserver::class);
        Address::observe(\App\Observers\AddressObserver::class);
    }
}
