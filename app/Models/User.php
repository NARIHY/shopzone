<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'workos_id', 'avatar', 'email_confirm_notification_sent'
    ];

    protected $hidden = ['workos_id', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Un utilisateur peut avoir plusieurs groupes
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Access\Group::class,
            'group_user',
            'user_id',
            'group_id'
        );
    }

    // Rôles de l'utilisateur via ses groupes (sans doublons)
    public function roles()
    {
        return \App\Models\Access\Role::whereHas('group', function ($q) {
            $q->whereIn('groups.id', $this->groups()->pluck('groups.id'));
        })->distinct();
    }

    // Accesseur : tous les rôles de l'utilisateur
    public function getAllRolesAttribute()
    {
        return $this->roles()->get();
    }

    // Vérifie si l'utilisateur a une permission via ses groupes → rôles → permissions
    public function hasPermission(string $permission): bool
    {
        return $this->groups()
            ->whereHas('roles.permissions', fn($q) => $q->where('name', $permission))
            ->exists();
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn($part) => Str::substr($part, 0, 1))
            ->implode('');
    }

    public function routeNotificationFor($driver, $notification = null)
    {
        return match ($driver) {
            'mail'  => $this->email,
            'nexmo' => $this->phone_number ?? null,
            default => null,
        };
    }
}