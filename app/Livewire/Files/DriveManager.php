<?php

namespace App\Livewire\Files;

use App\Models\Files\Media;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class DriveManager extends Component
{
    use WithFileUploads;

    public string $currentPath = 'drive'; // Dossier racine
    public $uploadingFiles = [];

    public string $newFolderName = '';
    public bool $showCreateFolder = false;

    protected $listeners = ['syncWithMedia'];

    public function mount($path = 'drive')
    {
        $this->currentPath = $path;
    }

    // Récupère les dossiers
    public function getFoldersProperty()
    {
        return collect(Storage::disk('public')->directories($this->currentPath))
            ->map(fn($dir) => [
                'name' => basename($dir),
                'path' => $dir,
            ]);
    }

    // Récupère les fichiers
    public function getFilesProperty()
    {
        return collect(Storage::disk('public')->files($this->currentPath))
            ->map(fn($file) => [
                'name' => basename($file),
                'path' => $file,
                'url'  => asset('storage/' . $file),
                'size' => Storage::disk('public')->size($file),
                'mime' => Storage::mimeType($file),
            ]);
    }

    // Créer un nouveau dossier
    public function createFolder()
    {
        $folderName = trim($this->newFolderName);
        if ($folderName === '') return;

        $fullPath = $this->currentPath.'/'.$folderName;

        if (!Storage::disk('public')->exists($fullPath)) {
            Storage::disk('public')->makeDirectory($fullPath);
            $this->dispatchBrowserEvent('alert', ['message' => "Dossier '$folderName' créé ✅"]);
        } else {
            $this->dispatchBrowserEvent('alert', ['message' => "Le dossier '$folderName' existe déjà ❌"]);
        }

        $this->newFolderName = '';
        $this->showCreateFolder = false;
    }

    // Renamed: Upload des fichiers (now handleUpload)
    public function handleUpload()
    {
        foreach ($this->uploadingFiles as $file) {
            $storedPath = $file->store($this->currentPath, 'public');

            Media::firstOrCreate(
                ['path' => $storedPath],
                [
                    'title'         => basename($storedPath),
                    'disk'          => 'public',
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'original_name' => $file->getClientOriginalName(),
                ]
            );
        }

        $this->uploadingFiles = [];
        $this->dispatch('refresh');
    }

    // Ouvrir un dossier
    public function openFolder($folderPath)
    {
        $this->currentPath = $folderPath;
    }

    // Remonter dans l'arborescence
    public function goBack()
    {
        $this->currentPath = Str::beforeLast($this->currentPath, '/');
        if ($this->currentPath === '') $this->currentPath = 'drive';
    }

    // Synchronisation avec Media
    public function syncWithMedia()
    {
        $allFiles = Storage::disk('public')->allFiles('drive');

        foreach ($allFiles as $file) {
            Media::firstOrCreate(
                ['path' => $file],
                [
                    'title'         => basename($file),
                    'disk'          => 'public',
                    'mime_type'     => Storage::mimeType($file),
                    'size'          => Storage::disk('public')->size($file),
                    'original_name' => basename($file),
                ]
            );
        }

        session()->flash('success', 'Synchronisation terminée ✅');
    }

    public function render()
    {
        return view('livewire.files.drive-manager');
    }
}