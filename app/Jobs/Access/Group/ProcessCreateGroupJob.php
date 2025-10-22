<?php

namespace App\Jobs\Access;

use App\Models\Access\Group;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCreateGroupJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public $uniqueFor = 3600;
    public Group $group;

    public function uniqueId(): string
    {
        return $this->group->id;
    }

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
        $this->group = Group::create($this->data);
        //IF SUCCESS DO NOTHING
    }
}
