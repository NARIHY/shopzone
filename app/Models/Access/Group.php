<?php

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /** @use HasFactory<\Database\Factories\Access\GroupFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'role_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Un groupe a un rôle.
     */
    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Un groupe contient plusieurs utilisateurs.
     */
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'group_user');
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->role && $this->role->hasPermission($permissionName);
    }
}
