<?php

namespace App\Jobs\Contact;

use App\Models\Contact\Contact;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcesseCreateContactJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public $uniqueFor = 3600;

    public Contact $contact;
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
        $this->contact = Contact::create($this->data);

        if($this->contact->email){
            $this->contact->notify(new \App\Notifications\Public\AnswerContactSendNotification($this->contact));
        }
        //IF SUCCESS DO NOTHING
    }

    public function uniqueId(): string
    {
        return $this->contact->id;
    }
}
