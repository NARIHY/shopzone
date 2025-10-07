<?php

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\Access\RoleFactory> */
    use HasFactory;

    protected $fillable = [
        'roleName',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

}
