<?php

namespace App\Listeners;

use App\Models\UserSessionHistory;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        UserSessionHistory::create([
            'user_id' => $event->user->id,
            'session_token' => session()->getId(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'login_at' => now(),
            'last_activity' => now(),
            'is_active' => true,
            'logout_at' => null,
        ]);
    }
}
