<?php

namespace App\Notifications\Access;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmeClientAccountNotification extends Notification
{
    use Queueable;

    private User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
            ->subject('Confirm Your Account and Start Shopping')
            ->greeting('Hello ' . $this->user->name . ',')
            ->line('Welcome to our e-commerce platform! To get started and enjoy all the shopping features, we just need you to confirm your account.')
            ->line('Your security is our top priority. We use the latest encryption technology to protect your personal and payment details, so you can shop with confidence.')
            ->line('To confirm your account and gain access to your personal dashboard, order history, and exclusive deals, simply click the button below.')
            ->action('Confirm My Account', route('admin.utils.verifyUserGroupsToAttacheClient', ['userId' => $this->user->id]))
            ->line('If you didn’t sign up for this account, feel free to ignore this email and your account will remain inactive.')
            ->line('Thank you for choosing us for your shopping experience. We’re excited to have you on board!')
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
