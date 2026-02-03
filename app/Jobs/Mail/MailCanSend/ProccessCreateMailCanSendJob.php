<?php

namespace App\Jobs\Mail\MailCanSend;

use App\Events\Utils\NotificationSent;
use App\Models\Mail\MailCanClientSend;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProccessCreateMailCanSendJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public $uniqueFor = 3600;

    public MailCanClientSend $mailCanClientSend;
    
    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->mailCanClientSend = MailCanClientSend::create($this->data);
        event(new NotificationSent('success','Queued. Waiting for confirmation to finalize mail can send create registration.'));
    }
}
