<?php

namespace App\Listeners;

use App\Events\Utils\NotificationSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleNotificationSent implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(NotificationSent $event): void
    {
        // 🔹 Exemple : Tu peux enregistrer en base
        // Notification::create([
        //     'type' => $event->type,
        //     'message' => $event->message,
        // ]);

        // 🔹 Exemple : Logger
        // \Log::info('Notification envoyée', [
        //     'type' => $event->type,
        //     'message' => $event->message,
        // ]);

        // 🔥 IMPORTANT : envoi du push WebSocket
        broadcast(new NotificationSent($event->type, $event->message))->toOthers();
    }
}
