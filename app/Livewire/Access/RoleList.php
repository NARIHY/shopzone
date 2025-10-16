<?php

namespace App\Livewire\Access;

use App\Models\Access\Role;
use Illuminate\Support\Facades\Cache;
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
        'page'   => ['except' => 1],
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

    /**
     * Requête optimisée + cache facultatif.
     */
    private function getRoles()
    {
        $cacheKey = 'roles_' . md5($this->search);

        return Cache::remember($cacheKey, 60, function () {
            return Role::select('id', 'roleName', 'description', 'created_at')
                ->when($this->search, function ($q) {
                    $search = "%{$this->search}%";
                    $q->where(function ($sub) use ($search) {
                        $sub->where('roleName', 'like', $search)
                            ->orWhere('description', 'like', $search);
                    });
                })
                ->with('groups')
                ->latest('id')
                ->paginate(20);
        });
    }

    /**
     * Rendu du composant.
     */
    public function render()
    {
        return view('livewire.access.role-list', [
            'roles' => $this->getRoles(),
        ]);
    }
}
