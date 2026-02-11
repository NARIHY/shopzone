<div>
    {{-- Message de succès --}}
    @if (session()->has('message'))
        <div class="bg-green-200 p-2 mb-2 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    {{-- Upload de l'image --}}
    <input type="file" wire:model="photo" accept="image/*">

    {{-- Éditeur PESDK --}}
    @if ($photo)
        <div id="editorContainer" style="width: 100%; height: 500px; margin-top: 10px;"></div>
        <button id="saveBtn" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">Sauvegarder</button>
    @endif

    {{-- Affichage de l'image éditée --}}
    @if ($editedPhoto)
        <div class="mt-4">
            <p>Photo éditée :</p>
            <img src="{{ asset('storage/' . $editedPhoto) }}" class="max-w-full border rounded">
        </div>
    @endif
</div>

@push('scripts')
<script type="module">
import PhotoEditorSDK from 'photoeditorsdk';

document.addEventListener('livewire:load', function () {
    const input = document.querySelector('input[type="file"]');
    const container = document.getElementById('editorContainer');
    const saveBtn = document.getElementById('saveBtn');

    let editor;

    input.addEventListener('change', function () {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            const imageUrl = e.target.result;

            // Détruire l'ancien éditeur si existant
            if (editor) editor.destroy();

            // Initialiser PESDK
            editor = new PhotoEditorSDK.UI.UI({
                container,
                image: imageUrl,
                license: "YOUR_LICENSE_KEY", // ⚠️ Remplace par ta clé PESDK
            });
        };
        reader.readAsDataURL(file);
    });

    saveBtn.addEventListener('click', async function () {
        if (!editor) return;

        const result = await editor.exportImage();
        Livewire.emit('saveEditedPhoto', result.image);
    });
});
</script>
@endpush
