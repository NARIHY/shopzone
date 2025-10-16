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
     * Supprimer un fichier média + son enregistrement
     */
    public function deleteMedia($id): void
    {
        $id = (int) $id;
        $media = Media::findOrFail($id);

        if (! $media) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => __('Media not found.')
            ]);
            return;
        }

        try {
            // supprime le fichier physique si existe
            if ($media->path && Storage::disk($media->disk)->exists($media->path)) {
                Storage::disk($media->disk)->delete($media->path);
            }

            $media->delete();

            // si modal ouverte pour ce fichier, fermer
            if ($this->selectedMedia?->id === $id) {
                $this->closeModal();
            }

            $this->resetPage();

            session()->flash('success', __("Media deleted successfully."));
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => __("Media deleted successfully.")
            ]);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', $e->getMessage());
        }
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
