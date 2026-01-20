<?php

namespace App\Http\Middleware;

use App\Services\CredService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLoginStreakMiddleware
{
    public function __construct(protected CredService $credService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $this->credService->updateLoginStreak($user);
        }

        return $next($request);
    }
}
