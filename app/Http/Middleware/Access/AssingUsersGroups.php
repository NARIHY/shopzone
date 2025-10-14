<?php

namespace App\Http\Middleware\Access;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class AssingUsersGroups
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->groups === null || Auth::user()->groups->isEmpty()) {
            $user = User::findOrFail(Auth::id());
            $user->notify(new \App\Notifications\Access\ConfirmeClientAccountNotification($user));
        }

        return $next($request);
    }
}
