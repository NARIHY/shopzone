<?php

namespace App\Http\Middleware\Access;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    protected array $ignoredRoutes = [
        'admin.unhautorize.users',
        'admin.dashboard',
        'admin.roleToPermission.index',//only super admin
        'admin.roleToPermission.update',//only super admin
        'login',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = User::findOrFail(Auth::id());

        if (!$user) {
            abort(403, 'Accès refusé.');
        }

        $routeName = $request->route()?->getName();

        // 1. Route ignorée
        if (!$routeName || in_array($routeName, $this->ignoredRoutes, true)) {
            return $next($request);
        }

        // 2. Seulement les routes admin.*
        if (!str_starts_with($routeName, 'admin.')) {
            return $next($request);
        }

        // 3. Extraire resource.action
        $parts = explode('.', $routeName);
        if (count($parts) < 3) {
            return $next($request);
        }

        $resource = $parts[1]; // ex: users
        $action   = $parts[2]; // ex: index, store

        // 4. Mapping via config
        $map = config('permissions.route_action_map', []);
        $permissionAction = $map[$action] ?? $action;

        // 5. Vérifier que l'action est autorisée dans la config
        $allowedActions = config('permissions.actions', []);
        if (!in_array($permissionAction, $allowedActions)) {
            return $next($request); // ou abort si tu veux être strict
        }

        $permission = "{$resource}.{$permissionAction}";

        // 6. Vérification finale
        if (!$user->hasPermission($permission)) {
            return redirect()
                ->route('admin.unhautorize.users')
                ->with('error', "Permission refusée : {$permission}");
        }

        return $next($request);
    }
}