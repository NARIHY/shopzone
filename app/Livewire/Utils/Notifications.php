<?php
// app/Livewire/Utils/Notifications.php

namespace App\Livewire\Utils;

use Livewire\Component;

class Notifications extends Component
{
    public $notifications = [];

    protected $listeners = [
        'notify' => 'addNotification'
    ];

    public function mount()
    {
        $this->notifications = [];
    }

    public function addNotification(string $type, string $message)
    {
        $id = 'notif-' . uniqid();

        $this->notifications[] = [
            'id' => $id,
            'type' => $type,
            'message' => $message,
            'time' => now()->format('H:i'),
        ];

        // Limite à 5 notifications
        if (count($this->notifications) > 5) {
            array_shift($this->notifications);
        }

        // Jouer le son via événement DOM
        $this->dispatch('play-notification-sound');
    }

    public function render()
    {
        return view('livewire.utils.notifications');
    }
}
