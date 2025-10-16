<?php

namespace App\Notifications\Public;

use App\Models\Contact\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnswerContactSendNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Contact $contact)
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
            ->subject(__('Response to your contact request: ' . $this->contact->subject))
            ->greeting('Hello ' . $this->contact->firstname . ' ' . $this->contact->lastname . ',')
            ->line('Thank you for reaching out to us regarding "' . $this->contact->subject . '". We sincerely appreciate your interest and the time you took to contact us.')
            ->line('Our team will review your request and get back to you as soon as possible.')
            ->line('Thank you for using our application and for your trust.')
            ->salutation(__('Best regards,') . "\n" . config('app.name'));
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
