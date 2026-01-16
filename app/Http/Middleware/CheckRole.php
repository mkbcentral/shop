<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  One or more roles to check (user needs at least one)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // If not authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has at least one of the required roles
        if (!empty($roles)) {
            if (!$user->hasAnyRole($roles)) {
                // Log unauthorized access attempt
                \Log::warning('Unauthorized role access attempt', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'required_roles' => $roles,
                    'user_roles' => $user->roles->pluck('slug')->toArray(),
                    'route' => $request->route()?->getName(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                ]);

                // For AJAX/Livewire requests, return JSON
                if ($request->ajax() || $request->wantsJson() || $request->header('X-Livewire')) {
                    return response()->json([
                        'message' => 'Vous n\'avez pas le rôle nécessaire pour effectuer cette action.',
                        'required_roles' => $roles,
                    ], 403);
                }

                // For regular requests, show 403 page
                abort(403, 'Vous n\'avez pas le rôle nécessaire pour accéder à cette page.');
            }
        }

        return $next($request);
    }
}
