<?php

namespace App\Observers;

use App\Models\Access\Role;
use Illuminate\Support\Facades\Cache;

class RoleObserver
{
    public function created(Role $role): void
    {
        Cache::forget('roles_input');
        Cache::forget('groups_input');
        Cache::forget('users_input');
    }

    public function updated(Role $role): void
    {
        Cache::forget('roles_input');
        Cache::forget('groups_input');
        Cache::forget('users_input');
    }

    public function deleted(Role $role): void
    {
        Cache::forget('roles_input');
        Cache::forget('groups_input');
        Cache::forget('users_input');
    }
}