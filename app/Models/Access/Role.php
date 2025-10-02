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
        'description',
        'is_active',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

}
