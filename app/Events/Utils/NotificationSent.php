<?php

namespace App\Events\Utils;

use Illuminate\Broadcasting\Channel; // Public channel pour test rapide
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $type;
    public string $message;

    /**
     * Create a new event instance.
     */
    public function __construct(string $type, string $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): array|Channel
    {
        // Channel public pour test rapide
        return [new Channel('notifications')];
    }

    /**
     * Nom de l'événement côté JS
     */
    public function broadcastAs(): string
    {
        return 'NotificationSent';
    }
}
