<?php

namespace App\Livewire\Mail\MailCanSendList;

use App\Models\Mail\MailCanClientSend;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class MailCanSendList extends Component
{
    use WithPagination;

    public string $search= '';
    public bool $showModal = false;
    public ?MailCanClientSend $selectedMailCanSend = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $listeners = [
        'showMailModal' => 'openMailModal',
        'destroyMailList' => 'cleanup'
    ];

    public function cleanup()
    {
        $this->resetPage();
        $this->search = '';
        $this->selectedMailCanSend = null;
        Cache::flush();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function applySearch()
    {
        $this->resetPage();
    }

    public function openMailModal($id)
    {
        $id = (int) $id;
        $this->selectedMailCanSend = MailCanClientSend::findOrFail($id);

        if( ! $this->selectedMailCanSend ) {
            $this->dispatchBrowserEvent('notification', [
                'type' => 'error',
                'message' => 'Mail Can Send not found.',
            ]);
            return;
        }
        $this->showModal = true;
    }


    public function closeMailModal()
    {
        $this->showModal = false;
        $this->selectedMailCanSend = null;
    }

    public function render()
    {
        return view('livewire.mail.mail-can-send-list.mail-can-send-list', [
            'mailCanSends' => $this->getMailCanSendListProperty(),
        ]);
    }

    private function getMailCanSendListProperty()
    {
        $query = MailCanClientSend::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
}
