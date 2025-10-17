<?php

namespace App\Livewire\Media;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Files\Media;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MediaList extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public ?Media $selectedMedia = null;

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
        $this->selectedMedia = Media::find($id);

        if (! $this->selectedMedia) {
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
        $this->selectedMedia = null;
    }


    /**
     * Render principal
     */
    public function render()
    {
        return view('livewire.media.media-list', [
            'media' => $this->getMedia(),
        ]);
    }

    private function getMedia()
    {
        $cacheKey = 'media_' . md5($this->search);

        return Cache::remember($cacheKey, 60, function () {
            return Media::select('id', 'title', 'original_name', 'path', 'disk', 'mime_type', 'size', 'created_at')
                ->when($this->search, function ($q) {
                    $search = "%{$this->search}%";
                    $q->where(function ($sub) use ($search) {
                        $sub->where('title', 'like', $search)
                            ->orWhere('original_name', 'like', $search);
                    });
                })
                ->with('products')
                ->latest()
                ->paginate(20);
        });
    }
}
