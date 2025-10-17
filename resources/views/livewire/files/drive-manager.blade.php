<div class="min-h-screen p-6 bg-white dark:bg-gray-900 rounded-xl shadow-md" id="drive-manager">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">📁 {{ $currentPath }}</h2>
        <div class="flex gap-2">
            <button wire:click="goBack" 
                    class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                ⬅ Retour
            </button>
            <button wire:click="$toggle('showCreateFolder')" 
                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                📂 Nouveau dossier
            </button>
            <button wire:click="syncWithMedia" 
                    class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition">
                🔄 Sync Media
            </button>
        </div>
    </div>

    <!-- Input pour créer un nouveau dossier -->
    @if($showCreateFolder)
        <div class="flex gap-2 mb-4">
            <input type="text" wire:model.defer="newFolderName" 
                   placeholder="Nom du dossier" 
                   class="flex-1 px-3 py-2 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button wire:click="createFolder" 
                    class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                Créer
            </button>
            <button wire:click="$set('showCreateFolder', false)" 
                    class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                Annuler
            </button>
        </div>
    @endif

    <!-- Zone de dépôt -->
    <div
        wire:ignore
        class="border-2 border-dashed border-gray-400 dark:border-gray-600 rounded-lg p-6 text-center mb-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition"
        onclick="document.getElementById('fileInput').click()"
        ondrop="dropHandler(event)" 
        ondragover="dragOverHandler(event)">
        <p class="text-gray-600 dark:text-gray-300">🚀 Glissez-déposez vos fichiers ici ou cliquez pour importer</p>
        <input type="file" multiple wire:model="uploadingFiles" class="hidden" id="fileInput">
    </div>

    @if ($uploadingFiles)
        <button wire:click="handleUpload" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">
            Téléverser
        </button>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
        <!-- Folders -->
        @foreach ($this->folders as $folder)
            <div wire:click="openFolder('{{ $folder['path'] }}')" 
                 class="cursor-pointer text-center hover:bg-gray-100 dark:hover:bg-gray-800 p-3 rounded transition">
                <div class="text-4xl">📁</div>
                <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $folder['name'] }}</div>
            </div>
        @endforeach

        <!-- Files -->
        @foreach ($this->files as $file)
            <div class="text-center bg-gray-50 dark:bg-gray-800 p-3 rounded transition">
                <a href="{{ $file['url'] }}" target="_blank">
                    @if (str_starts_with($file['mime'], 'image/'))
                        <!-- Image Preview -->
                        <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-32 object-cover rounded mb-2">
                    @elseif (str_starts_with($file['mime'], 'video/'))
                        <!-- Video Preview -->
                        <video class="w-full h-32 object-cover rounded mb-2" controls>
                            <source src="{{ $file['url'] }}" type="{{ $file['mime'] }}">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    @else
                        <!-- Fallback for other file types -->
                        <div class="text-4xl">📄</div>
                    @endif
                    <div class="text-sm truncate text-gray-800 dark:text-gray-100">{{ $file['name'] }}</div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<script>
function dragOverHandler(ev) {
    ev.preventDefault();
}

function dropHandler(ev) {
    ev.preventDefault();
    
    const files = ev.dataTransfer.files;
    const component = Livewire.find(document.getElementById('drive-manager').__livewire.id);

    for (let i = 0; i < files.length; i++) {
        component.upload('uploadingFiles', files[i], (uploadedFilename) => {
            console.log('Upload terminé :', uploadedFilename);
        }, () => {
            console.error('Erreur lors de l’upload');
        });
    }
}
</script>