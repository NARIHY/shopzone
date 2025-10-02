<?php

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    use HasFactory;

    protected $table = 'group_user'; // nom explicite de la table pivot

    protected $fillable = [
        'group_id',
        'user_id',
    ];

    /**
     * Relation avec le groupe.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Relation avec l’utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
