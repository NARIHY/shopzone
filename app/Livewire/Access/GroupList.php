<?php

namespace App\Livewire\Access;

use App\Models\Access\Group;
use Livewire\Component;
use Livewire\WithPagination;

class GroupList extends Component
{
    use WithPagination;
    public string $search = '';
    public bool $showModal = false;
    public ?Group $selectedGroup = null;

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
    public function applySearch(): void
    {
        $this->resetPage();
    }

    /**
     * Ouvre la modal pour un rôle donné
     */
    public function openRoleModal($id): void
    {
        $id = (int) $id;
        $this->selectedGroup = Group::findOrFail($id);

        if (! $this->selectedGroup) {
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
        $this->selectedGroup = null;
    }

    public function render()
    {
        return view('livewire.access.group-list', [
            'groups' => Group::query()
                ->when($this->search, fn($q) =>
                    $q->where('name', 'like', "%{$this->search}%")
                       ->orWhere('description', 'like', "%{$this->search}%")
                )
                ->latest()
                ->paginate(10),
        ]);
    }
}
