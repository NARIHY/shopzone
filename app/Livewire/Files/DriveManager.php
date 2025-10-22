<?php

namespace App\Livewire\Files;

use App\Models\Files\Media;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;

class DriveManager extends Component
{
    use WithFileUploads;

    public string $currentPath = 'drive';
    public $uploadingFiles = [];

    public string $newFolderName = '';
    public bool $showCreateFolder = false;

    public int $uploadProgress = 0;
    public int $syncProgress = 0;

    public ?string $fileToRename = null;
    public string $newFileName = '';

    protected $listeners = ['syncWithMedia'];

    public function mount($path = 'drive')
    {
        $this->currentPath = $path;
    }

    /** -------------------------------
     *  Dossiers & Fichiers
     * ------------------------------- */
    public function getFoldersProperty()
    {
        return collect(Storage::disk('public')->directories($this->currentPath))
            ->map(fn($dir) => [
                'name' => basename($dir),
                'path' => $dir,
            ]);
    }

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

    /** -------------------------------
     *  Création de dossier
     * ------------------------------- */
    public function createFolder()
    {
        $folderName = trim($this->newFolderName);
        if ($folderName === '') return;

        $fullPath = $this->currentPath . '/' . $folderName;

        if (!Storage::disk('public')->exists($fullPath)) {
            Storage::disk('public')->makeDirectory($fullPath);
            // emit event pour JS (alerte)
            $this->dispatch('alert', "Dossier '$folderName' créé ✅");
        } else {
            $this->dispatch('alert', "Le dossier '$folderName' existe déjà ❌");
        }

        $this->newFolderName = '';
        $this->showCreateFolder = false;
    }

    /** -------------------------------
     *  Upload avec progress bar
     * ------------------------------- */
    public function handleUpload()
    {
        $this->validate([
            'uploadingFiles.*' => 'required|file|max:102400', // max 100 MB par fichier
        ]);
        $total = count($this->uploadingFiles);
        if ($total === 0) return;

        // Indique au JS qu'on démarre
        $this->dispatch('uploadStart');

        $current = 0;

        foreach ($this->uploadingFiles as $file) {
            $storedPath = $file->store($this->currentPath, 'public');
            $title = $this->generateUniqueTitle(basename($storedPath));

            Media::firstOrCreate(
                ['path' => $storedPath],
                [
                    'title'         => $title,
                    'disk'          => 'public',
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'original_name' => $file->getClientOriginalName(),
                ]
            );

            $current++;
            $this->uploadProgress = intval(($current / $total) * 100);

            // emit pour la mise à jour client
            $this->dispatch('uploadProgress', $this->uploadProgress);
        }

        $this->uploadingFiles = [];
        $this->uploadProgress = 0;

        // Indique la fin
        $this->dispatch('uploadFinished');
    }

    /** -------------------------------
     *  Gestion des noms uniques
     * ------------------------------- */
    private function generateUniqueTitle($title)
    {
        $base = pathinfo($title, PATHINFO_FILENAME);
        $ext = pathinfo($title, PATHINFO_EXTENSION);
        $counter = 1;
        $newTitle = $title;

        while (Media::where('title', $newTitle)->exists()) {
            $newTitle = "{$base}_{$counter}" . ($ext ? ".{$ext}" : "");
            $counter++;
        }

        return $newTitle;
    }

    /** -------------------------------
     *  Navigation
     * ------------------------------- */
    public function openFolder($folderPath)
    {
        $this->currentPath = $folderPath;
    }

    public function goBack()
    {
        $this->currentPath = Str::beforeLast($this->currentPath, '/');
        if ($this->currentPath === '') $this->currentPath = 'drive';
    }

    /** -------------------------------
     *  Renommer un fichier
     * ------------------------------- */
    public function startRename($filePath)
    {
        $this->fileToRename = $filePath;
        $this->newFileName = basename($filePath);
    }

    public function renameFile()
    {
        if (!$this->fileToRename || trim($this->newFileName) === '') return;

        $oldPath = $this->fileToRename;
        $newPath = dirname($oldPath) . '/' . $this->newFileName;

        if (Storage::disk('public')->exists($newPath)) {
            $this->dispatch('alert', "Ce nom existe déjà ❌");
            return;
        }

        Storage::disk('public')->move($oldPath, $newPath);

        $media = Media::where('path', $oldPath)->first();
        if ($media) {
            $media->update([
                'path'  => $newPath,
                'title' => $this->newFileName,
            ]);
        }

        $this->fileToRename = null;
        $this->newFileName = '';
    }

    /** -------------------------------
     *  Synchronisation avec Media
     * ------------------------------- */
    public function syncWithMedia()
    {
        $allFiles = Storage::disk('public')->allFiles('drive');
        $total = count($allFiles);
        if ($total === 0) {
            session()->flash('success', 'Aucun fichier à synchroniser.');
            return;
        }

        // Indique au JS que la sync commence
        $this->dispatch('syncStart');

        $current = 0;

        foreach ($allFiles as $file) {
            Media::firstOrCreate(
                ['path' => $file],
                [
                    'title'         => $this->generateUniqueTitle(basename($file)),
                    'disk'          => 'public',
                    'mime_type'     => Storage::mimeType($file),
                    'size'          => Storage::disk('public')->size($file),
                    'original_name' => basename($file),
                ]
            );

            $current++;
            $this->syncProgress = intval(($current / $total) * 100);

            // emit pour mise à jour client
            $this->dispatch('syncProgress', $this->syncProgress);
        }

        $this->syncProgress = 0;

        // Indique la fin
        $this->dispatch('syncFinished');

        session()->flash('success', 'Synchronisation terminée ✅');
    }

    public function render()
    {
        return view('livewire.files.drive-manager');
    }
}
