<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
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

        Category::observe(\App\Observers\CategoryObserver::class);

        VerifyEmail::toMailUsing(function (object $notificable, string $url) {
            return (new MailMessage)
                ->subject('Verifica tu correo electrónico')
                ->greeting('Hola, ' . $notificable->name . '!')
                ->line('Por favor, haz click en el siguiente enlace para verificar tu correo electrónico:')
                ->action('Verificar mi correo', $url)
                ->line('Si no creaste esta cuenta, ignora este correo. No es necesario que realices ninguna otra acción.');
        });
    }
}
