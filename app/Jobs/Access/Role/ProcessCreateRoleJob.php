<?php

namespace App\Jobs\Access\Role;

use App\Models\Access\Role;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCreateRoleJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public $uniqueFor = 3600;

    public Role $role;
    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
        //
    }
    public function uniqueId(): string
    {
        return $this->role->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->role = Role::create($this->data);
        //IF SUCCESS DO NOTHING
    }
}
