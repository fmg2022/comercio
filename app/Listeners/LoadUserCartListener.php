<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class LoadUserCartListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = User::findOrFail($event->user->id);
        $this->cartService->loadCartFromDatabase($user);
    }
}
