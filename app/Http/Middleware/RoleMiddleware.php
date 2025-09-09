<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();
        \Log::info('RoleMiddleware', [
            'user_id' => $user ? $user->id : null,
            'user_email' => $user ? $user->email : null,
            'user_role' => $user ? $user->role : null,
            'expected_roles' => $roles,
            'uri' => $request->getRequestUri(),
        ]);
        if (! $user || ! in_array($user->role, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
