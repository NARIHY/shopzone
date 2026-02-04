<?php

namespace App\Jobs\Mail\MailCanSend;

use App\Events\Utils\NotificationSent;
use App\Models\Mail\MailCanClientSend;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProccessUpdateMailCanSendJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public $uniqueFor = 3600;

    public MailCanClientSend $mailCanClientSend;

    private MailCanClientSend $originalMailCanClientSend;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data, MailCanClientSend $mailCanClientSend)
    {
        $this->mailCanClientSend = $mailCanClientSend;
    }

    
    public function uniqueId(): string
    {
        return $this->mailCanClientSend->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->originalMailCanClientSend = MailCanClientSend::findOrFail($this->mailCanClientSend->id);
        if (!$this->originalMailCanClientSend){
            event(new NotificationSent('warning','Mail Can Send not found. Update aborted.'));
        }

        $this->originalMailCanClientSend->update($this->data);
        event(new NotificationSent('success','Mail Can Send updated successfully.'));
    }
}
