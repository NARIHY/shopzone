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
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Un groupe contient plusieurs utilisateurs.
     */
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'group_user');
    }
}
