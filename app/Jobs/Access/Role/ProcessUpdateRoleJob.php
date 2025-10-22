<?php

namespace App\Jobs\Access\Role;

use App\Models\Access\Role;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessUpdateRoleJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    public $uniqueFor = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct(public ?Role $role, public array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->role && Role::findOrFail($this->role->id))
        {
            $role = Role::find($this->role->id);
            $role->update($this->data);
        }
    }
}
