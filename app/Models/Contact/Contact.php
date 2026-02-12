<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Contact extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'subject',
        'message',
    ];

    public function answers()
    {
        return $this->hasMany(
            \App\Models\Mail\AnswerClient\AnswerClientMail::class,
            'contact_id'
        );
    }


}
