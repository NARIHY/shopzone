<?php

namespace App\Models\Mail\AnswerClient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerClientMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'content',
        'media_path',
        'sender_type', 
        'contact_id',
    ];

    public function contact()
    {
        return $this->belongsTo(
            \App\Models\Contact\Contact::class,
            'contact_id'
        );
    }

}
