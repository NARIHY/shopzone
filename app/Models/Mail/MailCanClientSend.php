<?php

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailCanClientSend extends Model
{
    use HasFactory;

    protected $table = 'mail_can_client_sends';

    protected $fillable = [
        'mail_limit',
        'is_active',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'mail_limit' => 'integer',
        'is_active'  => 'boolean',
    ];
}
