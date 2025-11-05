<?php

namespace App\Events\Utils;

use Illuminate\Broadcasting\Channel; // public channel pour tester rapidement
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $message;

    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        // Utiliser Channel (public) pour test rapide
        return [new Channel('notifications')];
    }

    public function broadcastAs()
    {
        return 'NotificationSent'; // nom de l’événement JS
    }
}
