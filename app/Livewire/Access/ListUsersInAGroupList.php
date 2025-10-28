<?php

namespace App\Livewire\Access;

use App\Models\Access\Group;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class ListUsersInAGroupList extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public ?Group $selectedGroup = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $listeners = [
        'destroyGroupUserList' => 'cleanup',
        'showGroupUsersModal' => 'openGroupUsersModal',
    ];

    public function cleanup(): void
    {
        $this->resetPage();
        $this->search = '';
        $this->selectedGroup = null;
        Cache::flush();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function applySearch(): void
    {
        $this->resetPage();
    }

    public function openGroupUsersModal($id): void
    {
        $id = (int) $id;
        $this->selectedGroup = Group::with('users')->find($id);

        if (! $this->selectedGroup) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => __('Group not found.')
            ]);
            return;
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedGroup = null;
    }

    public function render()
    {
        return view('livewire.access.list-users-in-a-group-list', [
            'groups' => $this->getGroups(),
        ]);
    }

    private function getGroups()
    {
        return Cache::remember('group_users_' . md5($this->search), 60, function () {
            return Group::with(['roles:id,roleName', 'users:id,name,email'])
                ->select('id', 'name', 'description', 'role_id', 'is_active', 'created_at')
                ->when($this->search, function ($q) {
                    $search = "%{$this->search}%";
                    $q->where(function ($sub) use ($search) {
                        $sub->where('name', 'like', $search)
                            ->orWhere('description', 'like', $search)
                            ->orWhereHas('users', function ($userQ) use ($search) {
                                $userQ->where('name', 'like', $search)
                                    ->orWhere('email', 'like', $search);
                            });
                    });
                })
                ->latest()
                ->paginate(20);
        });
    }
}
