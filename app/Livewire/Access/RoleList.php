<?php

namespace App\Livewire\Access;

use App\Models\Access\Role;
use Livewire\Component;
use Livewire\WithPagination;

class RoleList extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public ?Role $selectedRole = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $listeners = [
        'showRoleModal' => 'openRoleModal',
    ];

    /**
     * Reset pagination quand on tape dans la recherche
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Ouvre la modal pour un rôle donné
     */
    public function openRoleModal($id): void
    {
        $id = (int) $id;
        $this->selectedRole = Role::findOrFail($id);

        if (! $this->selectedRole) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => __('Role not found.')
            ]);
            return;
        }

        $this->showModal = true;
    }

    /**
     * Ferme la modal
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedRole = null;
    }

    public function render()
    {
        return view('livewire.access.role-list', [
            'roles' => Role::query()
                ->when($this->search, fn($q) =>
                    $q->where('name', 'like', "%{$this->search}%")
                       ->orWhere('description', 'like', "%{$this->search}%")
                )
                ->latest()
                ->paginate(10),
        ]);
    }
}
