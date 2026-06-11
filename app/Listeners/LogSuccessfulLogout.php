<?php

namespace App\Listeners;

use App\Models\UserSessionHistory;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogout
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
    public function handle(Logout $event): void
    {
        UserSessionHistory::where('user_id', $event->user->id)
            ->where('session_token', session()->getId())
            ->whereNull('logout_at')
            ->update(['logout_at' => now(), 'is_active' => false]);
    }
}
