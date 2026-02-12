<?php

namespace App\Notifications\Client\Answer;

use App\Models\Contact\Contact;
use App\Models\Mail\AnswerClient\AnswerClientMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mime\MimeTypes;

class MailClientAnsweerNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Contact $contact, public array $answerClientMail)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello ' . $this->contact->lastname . ' ' . $this->contact->firstname . ',')
            ->subject($this->answerClientMail['subject'])
            ->attach(
                Storage::disk('public')->path($this->answerClientMail['media_path']),
                [
                    'as' => basename($this->answerClientMail['media_path']),
                    'mime' => (new MimeTypes())->guessMimeType($this->answerClientMail['media_path']),
                ]
            )
            ->line($this->answerClientMail['content'])
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
