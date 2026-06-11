<?php

namespace App\Http\Middleware;

use App\Models\UserSessionHistory;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserSessionActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (!Auth::check() || !session()->isStarted()) {
            return;
        }

        $userId = Auth::user()->id;
        $sessionId = session()->getId();
        $cacheKey = "last_activity_update_{$userId}_{$sessionId}";

        $lastUpdate = Cache::get($cacheKey);
        if ($lastUpdate && now()->diffInMinutes($lastUpdate) < 5) {
            return;
        }

        UserSessionHistory::where('user_id', $userId)
            ->where('session_token', $sessionId)
            ->whereNull('logout_at')
            ->update(['last_activity' => now()]);

        Cache::put($cacheKey, now(), 360); // 6 minutos
    }
}
