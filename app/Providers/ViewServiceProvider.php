<?php

namespace App\Providers;

use App\View\Composers\OfferComposer;
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
        // Compartir datos con múltiples vistas
        View::composer(['layouts.partials.header', 'pages.home.*'], OfferComposer::class);
    }
}
