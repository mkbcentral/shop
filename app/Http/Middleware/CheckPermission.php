<?php

namespace App\Http\Middleware;

use App\Services\MenuService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Mapping des permissions vers les codes de menu
     */
    private const PERMISSION_TO_MENU = [
        'categories.view' => 'categories.index',
        'products.view' => 'products',
        'sales.view' => 'sales',
        'sales.create' => 'sales',
        'sales.edit' => 'sales',
        'purchases.view' => 'purchases',
        'purchases.create' => 'purchases',
        'purchases.edit' => 'purchases',
        'clients.view' => 'clients',
        'suppliers.view' => 'suppliers',
        'stores.view' => 'stores',
        'stores.create' => 'stores',
        'stores.edit' => 'stores',
        'transfers.view' => 'transfers',
        'users.view' => 'users',
        'roles.view' => 'roles',
        'roles.create' => 'roles',
        'roles.edit' => 'roles',
        'reports.sales' => 'reports',
        'reports.stock' => 'reports',
        'system.settings' => 'settings',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions  One or more permissions to check (user needs at least one)
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        // If not authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin bypasses all permission checks
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Check if user has at least one of the required permissions
        if (!empty($permissions)) {
            $hasPermission = false;
            $hasMenuAccess = false;
            $menuService = app(MenuService::class);

            foreach ($permissions as $permission) {
                if ($user->hasPermission($permission)) {
                    $hasPermission = true;

                    // Check menu access for this permission
                    $menuCode = self::PERMISSION_TO_MENU[$permission] ?? null;
                    if ($menuCode && $menuService->hasAccessToMenu($user, $menuCode)) {
                        $hasMenuAccess = true;
                        break;
                    }
                }
            }

            // User must have both permission AND menu access
            if (!$hasPermission || !$hasMenuAccess) {
                // Log unauthorized access attempt
                \Log::warning('Unauthorized access attempt', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'required_permissions' => $permissions,
                    'has_permission' => $hasPermission,
                    'has_menu_access' => $hasMenuAccess,
                    'route' => $request->route()?->getName(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                ]);

                // For AJAX/Livewire requests, return JSON
                if ($request->ajax() || $request->wantsJson() || $request->header('X-Livewire')) {
                    return response()->json([
                        'message' => 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.',
                        'required_permissions' => $permissions,
                    ], 403);
                }

                // For regular requests, show 403 page
                abort(403, 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
            }
        }

        return $next($request);
    }
}
