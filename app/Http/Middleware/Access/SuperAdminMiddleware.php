<?php

namespace App\Http\Middleware\Access;

use App\Events\Utils\NotificationSent;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Empêche la boucle de redirection : on laisse passer la route de redirection
        if ($request->routeIs('admin.unhautorize.users')) {
            return $next($request);
        }

        $user = Auth::user();

        if (!$user) {
            abort(403, 'Accès refusé.');
        }

        // Vérifie si l'utilisateur appartient au groupe "Super Admins"
        $isSuperAdmin = $user->groups()
            ->where('name', 'Super Admins')
            ->exists();

        if (!$isSuperAdmin) {
            event(new NotificationSent('warning', 'Action réservée aux Super Admins.'));
            return redirect()->route('admin.unhautorize.users');
        }

        return $next($request);
    }
}
