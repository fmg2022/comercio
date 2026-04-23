<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Category;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Gate;
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
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        OrderProduct::observe(\App\Observers\OrderProductObserver::class);
        Category::observe(\App\Observers\CategoryObserver::class);
        Address::observe(\App\Observers\AddressObserver::class);
    }
}
