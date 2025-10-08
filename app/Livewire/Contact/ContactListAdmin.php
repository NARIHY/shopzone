<?php

namespace App\Livewire\Contact;

use App\Models\Contact\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactListAdmin extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public ?Contact $selectedContact = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $listeners = [
        'showMediaModal' => 'openMediaModal',
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
     * Ouvre la modal pour un media donné
     */
    public function openMediaModal($id): void
    {
        $id = (int) $id;
        $this->selectedContact = Contact::findOrFail($id);

        if (! $this->selectedContact) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => __('Media not found.')
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
        $this->selectedContact = null;
    }

    
    public function render()
    {
        return view('livewire.contact.contact-list-admin', [
            'contacts' => Contact::query()
                ->when($this->search, fn($q) =>
                    $q->where('firstName', 'like', "%{$this->search}%")
                       ->orWhere('lastName', 'like', "%{$this->search}%")
                       ->orWhere('subject', 'like', "%{$this->search}%")
                )
                ->latest()
                ->paginate(10),
        ]);
    }
}
