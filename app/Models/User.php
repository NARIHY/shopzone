<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'workos_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'workos_id',
        'remember_token',
    ];

    /**
     * Get the user's initials.
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function groups()
    {
        return $this->belongsToMany(\App\Models\Access\Group::class, 'group_user');
    }

    public function routeNotificationFor($driver, $notification = null)
    {
        if ($driver === 'mail') {
            return $this->email;
        }

        if ($driver === 'nexmo') {
            return $this->phone_number;
        }
        
        // Add other drivers as needed
        return null;
    }

    public function roles()
    {
        return $this->belongsToMany(
            \App\Models\Access\Role::class,
            'group_role',      // table pivot entre groups et roles
            'group_id',        // clé dans la table pivot pour le group
            'role_id'          // clé dans la table pivot pour le role
        )->whereIn('group_id', $this->groups->pluck('id'));
    }


    public function getAllRolesAttribute()
    {
        return \App\Models\Access\Role::whereHas('groups.users', function ($query) {
            $query->where('users.id', $this->id);
        })->get();
    }


    public function hasPermission(string $permission): bool
    {
        return $this->roles
            ->flatMap(fn($role) => $role->permissions)
            ->pluck('name')
            ->contains($permission);
    }
}
