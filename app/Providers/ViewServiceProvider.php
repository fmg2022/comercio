<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer(['layouts.partials.header', 'pages.home.*'], function ($view) {
            $user = auth()->user();
            if ($user) {
                $user->loadMissing('cart');
                $cart = $user->cart;
            } else {
                $cart = null;
            }
            $view->with('categories', \App\Models\Category::getFullTree())
                ->with('offers', \App\Models\Offer::with(['offerTemplate:id,name,offer_type_id,buy_qty,pay_qty', 'offerTemplate.offerType:id,code'])->active()->get()->keyBy('id'))
                ->with('cart', $cart);
        });
    }
}
