<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use function Symfony\Component\Clock\now;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // sharing daa with multiple views
        View::composer(['pages.index', 'pages.home.product.show', 'pages.home.product.list', 'pages.home.cart.index'], function ($view) {
            $view->with('categories', \App\Models\Category::getFullTree())
                ->with('offers', \App\Models\Offer::with(['offerTemplate:id,name,offer_type_id,buy_qty,pay_qty', 'offerTemplate.offerType:id,code'])->active()->get()->keyBy('id')->toArray());
        });
    }
}
