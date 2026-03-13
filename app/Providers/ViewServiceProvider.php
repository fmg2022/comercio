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
        View::composer(['pages.index', 'pages.home.product.show', 'pages.home.product.list'], function ($view) {
            $view->with('categories', \App\Models\Category::getFullTree());
            // Add offers actives
            // ->with('offers', OfertasModeloNecesario::where('activo', VERDADERO)->get());
        });
    }
}
