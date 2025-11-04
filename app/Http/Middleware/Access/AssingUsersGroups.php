<?php

namespace App\Http\Middleware\Access;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\Access\ConfirmeClientAccountNotification;
use Carbon\Carbon;

class AssingUsersGroups
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::findOrFail(Auth::user()->id);

        if ($user && ($user->groups === null || $user->groups->isEmpty())) {

            if (
                !$user->email_confirm_notification_sent_at ||
                Carbon::parse($user->updated_at)->addHours(2)->isPast()
            ) {
                $user->notify(new ConfirmeClientAccountNotification($user));
                $user->update([
                    'email_confirm_notification_sent_at' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
