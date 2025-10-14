<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilsUsersController extends Controller
{
    public function verifyUserGroupsToAttacheCLient(Request $request, string $userId)
    {
        $user = User::findOrFail(Auth::user()->id);

        $user->groups()->attach(1);

        $user->notify(new \App\Notifications\Access\SuccessClientRegistrationNotification($user));
        
        return redirect()->route('public.home')->with('success', 'Your account has been successfully confirmed. You can now access all features.');
    }
}
