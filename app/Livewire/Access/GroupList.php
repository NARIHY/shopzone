<?php

namespace App\Livewire\Access;

use App\Models\Access\Group;
use Illuminate\Support\Facades\Cache;
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
        'destroyGroupList'=> 'cleanup'
    ];

    public function cleanup(): void
    {
        $this->resetPage();
        $this->search = '';
        $this->selectedGroup = null;
        Cache::flush();
    }

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
            'groups' => $this->getGroups(),
        ]);
    }

    private function getGroups()
    {
        $cache = Cache::get('groups_' . md5($this->search));

        return Cache::remember('groups_' . md5($this->search), 60, function () {
            return Group::with(['role:id,roleName', 'users:id,name'])
                ->select('id', 'name', 'description', 'role_id', 'created_at')
                ->when($this->search, function ($q) {
                    $search = "%{$this->search}%";
                    $q->where(function ($sub) use ($search) {
                        $sub->where('name', 'like', $search)
                            ->orWhere('description', 'like', $search);
                    });
                })
                ->latest()
                ->paginate(20);
        });
    }
}
