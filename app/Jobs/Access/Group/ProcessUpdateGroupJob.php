<?php

namespace App\Jobs\Access\Group;

use App\Models\Access\Group;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessUpdateGroupJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    public $uniqueFor = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct(public ?Group $group, public array $data)
    {
        //
    }

    public function uniqueId(): string
    {
        return $this->group->id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if(Group::findOrFail($this->group->id)) {
            $group = Group::find($this->group->id);
            $group->update($this->data);
        }
    }
}
