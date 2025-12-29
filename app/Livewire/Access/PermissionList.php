<?php

namespace App\Livewire\Access;

use App\Models\Access\Permission;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class PermissionList extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public ?Permission $selectedPermission = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'page'   => ['except' => 1],
    ];

    protected $listeners = [
        'showPermissionModal' => 'openPermissionModal',
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
     * Ouvre la modal pour une permission donnée
     */
    public function openPermissionModal($id): void
    {
        $id = (int) $id;
        $this->selectedPermission = Permission::find($id);

        if (! $this->selectedPermission) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => __('Permission not found.')
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
        $this->selectedPermission = null;
    }

    /**
     * Requête optimisée + cache facultatif
     */
    private function getPermissions()
    {
        return Permission::select('id', 'name', 'description', 'is_active', 'created_at')
                ->when($this->search, function ($q) {
                    $search = "%{$this->search}%";
                    $q->where(function ($sub) use ($search) {
                        $sub->where('name', 'like', $search)
                            ->orWhere('description', 'like', $search);
                    });
                })
                ->latest('id')
                ->paginate(20);
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        return view('livewire.access.permission-list', [
            'permissions' => $this->getPermissions(),
        ]);
    }
}
