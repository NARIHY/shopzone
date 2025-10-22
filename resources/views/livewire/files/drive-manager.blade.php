<div class="min-h-screen p-6 bg-white dark:bg-gray-900 rounded-xl shadow-md" id="drive-manager">

    <!-- HEADER -->
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
            <button id="btn-sync" wire:click="syncWithMedia" 
                    class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition">
                🔄 Sync Media
            </button>
        </div>
    </div>

    <!-- PROGRESS SYNC (DOM contrôlé par JS via events) -->
    <div id="syncProgressWrapper" class="hidden w-full mb-4">
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div id="syncProgressBar" class="h-2.5 rounded-full" style="width: 0%;"></div>
        </div>
        <div id="syncProgressText" class="text-xs text-gray-600 mt-1"></div>
    </div>

    <!-- CRÉER DOSSIER -->
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

    <!-- UPLOAD -->
    <div
        wire:ignore
        class="border-2 border-dashed border-gray-400 dark:border-gray-600 rounded-lg p-6 text-center mb-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition"
        onclick="document.getElementById('fileInput').click()"
        ondrop="dropHandler(event)" 
        ondragover="dragOverHandler(event)">
        <p class="text-gray-600 dark:text-gray-300">{{__('🚀 Drag and drop your files here or click to import')}}</p>
        <input type="file" multiple wire:model="uploadingFiles" class="hidden" id="fileInput">
    </div>

    @if ($uploadingFiles)
        <button id="btn-upload" wire:click="handleUpload" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">
            {{__('Upload')}}
        </button>
    @endif

    <!-- PROGRESS UPLOAD (DOM contrôlé par JS via events) -->
    <div id="uploadProgressWrapper" class="hidden w-full mt-3">
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div id="uploadProgressBar" class="h-2.5 rounded-full" style="width: 0%;background-color: #1e3a8a;"></div>
        </div>
        <div id="uploadProgressText" class="text-xs text-gray-600 mt-1"></div>
    </div>

    <!-- CONTENU -->
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
                @if($fileToRename === $file['path'])
                    <input type="text" wire:model.defer="newFileName" class="w-full px-2 py-1 rounded border" />
                    <div class="flex justify-center gap-2 mt-2">
                        <button wire:click="renameFile" class="px-2 py-1 bg-green-500 text-white rounded">💾</button>
                        <button wire:click="$set('fileToRename', null)" class="px-2 py-1 bg-gray-400 text-white rounded">❌</button>
                    </div>
                @else
                    <a href="{{ $file['url'] }}" target="_blank">
                        @if (str_starts_with($file['mime'], 'image/'))
                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-32 object-cover rounded mb-2">
                        @elseif (str_starts_with($file['mime'], 'video/'))
                            <video class="w-full h-32 object-cover rounded mb-2" controls>
                                <source src="{{ $file['url'] }}" type="{{ $file['mime'] }}">
                            </video>
                        @else
                            <div class="text-4xl">📄</div>
                        @endif
                    </a>
                    <div class="text-sm truncate text-gray-800 dark:text-gray-100">{{ $file['name'] }}</div>
                    <button wire:click="startRename('{{ $file['path'] }}')" class="text-xs text-blue-500 mt-1">✏️ Renommer</button>
                @endif
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
        component.upload('uploadingFiles', files[i]);
    }
}

/* ---------- Elements progress ---------- */
const uploadWrapper = document.getElementById('uploadProgressWrapper');
const uploadBar = document.getElementById('uploadProgressBar');
const uploadText = document.getElementById('uploadProgressText');

const syncWrapper = document.getElementById('syncProgressWrapper');
const syncBar = document.getElementById('syncProgressBar');
const syncText = document.getElementById('syncProgressText');

/* ---------- Native Livewire upload events (client side) ---------- */
window.addEventListener('livewire-upload-start', () => {
    if (uploadWrapper) uploadWrapper.classList.remove('hidden');
    if (uploadBar) uploadBar.style.width = '0%';
    if (uploadText) uploadText.textContent = '';
});

window.addEventListener('livewire-upload-progress', (e) => {
    const progress = e.detail && (e.detail.progress ?? e.detail.percentage ?? 0);
    if (uploadBar) uploadBar.style.width = progress + '%';
    if (uploadText) uploadText.textContent = progress + '%';
});

window.addEventListener('livewire-upload-finish', () => {
    if (uploadText) uploadText.textContent = 'Upload envoyé, traitement serveur en cours…';
});

window.addEventListener('livewire-upload-error', () => {
    if (uploadText) uploadText.textContent = 'Erreur lors de l’upload';
    if (uploadBar) uploadBar.classList.add('bg-red-500');
    setTimeout(() => {
        if (uploadWrapper) uploadWrapper.classList.add('hidden');
        if (uploadBar) uploadBar.style.width = '0%';
    }, 1500);
});


</script>

@section('livewire-scripts')
    <script>
    /* ---------- Events émis depuis PHP via $this->emit(...) ---------- */
    /* upload */
    Livewire.on('uploadStart', () => {
        if (uploadWrapper) uploadWrapper.classList.remove('hidden');
        if (uploadBar) uploadBar.style.width = '0%';
        if (uploadText) uploadText.textContent = 'Traitement serveur...';
    });
    Livewire.on('uploadProgress', (value) => {
        const progress = Number(value) || 0;
        if (uploadBar) uploadBar.style.width = progress + '%';
        if (uploadText) uploadText.textContent = progress + '%';
    });
    Livewire.on('uploadFinished', () => {
        if (uploadBar) uploadBar.style.width = '100%';
        if (uploadText) uploadText.textContent = 'Terminé ✓';
        setTimeout(() => {
            if (uploadWrapper) uploadWrapper.classList.add('hidden');
            if (uploadBar) uploadBar.style.width = '0%';
            if (uploadText) uploadText.textContent = '';
        }, 1000);
    });

    /* sync */
    Livewire.on('syncStart', () => {
        if (syncWrapper) syncWrapper.classList.remove('hidden');
        if (syncBar) syncBar.style.width = '0%';
        if (syncText) syncText.textContent = 'Démarrage...';
    });
    Livewire.on('syncProgress', (value) => {
        const progress = Number(value) || 0;
        if (syncBar) syncBar.style.width = progress + '%';
        if (syncText) syncText.textContent = progress + '%';
    });
    Livewire.on('syncFinished', () => {
        if (syncBar) syncBar.style.width = '100%';
        if (syncText) syncText.textContent = 'Synchronisation terminée ✓';
        setTimeout(() => {
            if (syncWrapper) syncWrapper.classList.add('hidden');
            if (syncBar) syncBar.style.width = '0%';
            if (syncText) syncText.textContent = '';
        }, 1200);
    });

    /* alert (optionnel) */
    Livewire.on('alert', (message) => {
        // tu peux remplacer par ta propre UI toast
        alert(message);
    });
    </script>
@endsection
