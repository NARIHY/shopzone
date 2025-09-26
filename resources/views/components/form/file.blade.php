@props([
    'id' => null,
    'name',
    'label' => null,
    'multiple' => false,
    'accept' => '*/*',          // ajuste si tu veux limiter les types (ex: 'image/*')
    'value' => null             // optionnel : URL string or array of URLs (fichiers déjà présents)
])

<div
    x-data="fileDropzone({
        multiple: {{ $multiple ? 'true' : 'false' }},
        name: '{{ $name }}',
        existing: {{ json_encode($value ?? []) }},
        accept: '{{ $accept }}'
    })"
    x-init="init()"
    class="flex flex-col gap-2"
>
    {{-- Label --}}
    @if($label)
        <label for="{{ $id ?? $name }}" class="text-gray-700 dark:text-gray-200 font-medium">
            {{ $label }}
        </label>
    @endif

    {{-- Input (caché) --}}
    <input
        x-ref="input"
        type="file"
        :name="inputName"
        id="{{ $id ?? $name }}"
        class="sr-only"
        :multiple="multiple"
        :accept="accept"
        @change="handleFiles($event.target.files)"
    />

    {{-- Dropzone --}}
    <div
        @click="$refs.input.click()"
        @dragover.prevent="dragOver = true"
        @dragenter.prevent="dragOver = true"
        @dragleave.prevent="dragOver = false"
        @drop.prevent="onDrop($event)"
        :class="{
            'border-blue-400 bg-blue-50 dark:bg-blue-900': dragOver,
            'border-dashed': true,
            'border-2': true
        }"
        class="w-full rounded-lg px-4 py-6 text-center cursor-pointer transition-colors"
        role="button"
        tabindex="0"
    >
        <div class="flex flex-col items-center gap-2">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M7 16v-4a4 4 0 014-4h2a4 4 0 014 4v4M12 12v8" />
            </svg>

            <div class="text-sm text-gray-600 dark:text-gray-300">
                <span x-show="!files.length">Glisser-déposer des fichiers ici, ou <span class="underline">cliquer pour sélectionner</span></span>
                <span x-show="files.length" x-text="files.length + (multiple ? ' fichier(s) sélectionné(s)' : ' fichier sélectionné')"></span>
            </div>

            <div class="text-xs text-gray-500 dark:text-gray-400" x-text="acceptMessage"></div>
        </div>
    </div>

    {{-- Previews / liste --}}
    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        <template x-for="(f, idx) in files" :key="f.key">
            <div class="relative border rounded-lg overflow-hidden p-2 bg-white dark:bg-gray-800">
                <div class="h-28 w-full flex items-center justify-center overflow-hidden">
                    <template x-show="f.isImage">
                        <img :src="f.preview" class="object-cover h-full w-full" alt="" />
                    </template>
                    <template x-show="!f.isImage">
                        <div class="text-xs text-center px-2 break-words">
                            <div x-text="f.name"></div>
                            <div class="text-gray-500 text-[11px]" x-text="f.humanSize"></div>
                        </div>
                    </template>
                </div>

                <div class="mt-2 flex justify-between items-center gap-2">
                    <div class="text-xs truncate" x-text="f.name"></div>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="remove(idx)" class="text-red-600 hover:text-red-800 text-sm">Suppr</button>
                    </div>
                </div>

                {{-- Si c'est un fichier existant, envoyez un champ hidden pour côté serveur --}}
                <input
                    x-show="f.isExisting"
                    type="hidden"
                    :name="'existing_{{ $name }}[]'"
                    :value="f.url"
                />
            </div>
        </template>
    </div>

    {{-- Erreur serveur (si Laravel renvoie) --}}
    @error($name)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

{{-- Alpine component JS (insérer une seule fois dans la page si possible) --}}
<script>
    function fileDropzone({ multiple = false, name = 'file', existing = [], accept = '*' }) {
        return {
            multiple: multiple,
            name: name,
            accept: accept,
            dragOver: false,
            files: [], // éléments [{ file?, name, preview, size, key, isImage, isExisting, url?, humanSize }]
            inputName: multiple ? (name + '[]') : name,
            acceptMessage: accept === '*' ? '' : 'Types autorisés: ' + accept,

            init() {
                // si des fichiers "existants" sont fournis (URL string ou array), on les affiche
                if (existing) {
                    let arr = Array.isArray(existing) ? existing : [existing];
                    arr.forEach((url, i) => {
                        if (!url) return;
                        const nameFromUrl = url.split('/').pop() || ('fich_'+i);
                        this.files.push({
                            key: 'existing-' + i + '-' + Date.now(),
                            name: nameFromUrl,
                            preview: url,
                            size: null,
                            isImage: /\.(jpe?g|png|gif|webp|svg)$/i.test(url),
                            isExisting: true,
                            url: url,
                            humanSize: ''
                        });
                    });
                }
            },

            handleFiles(list) {
                const fileList = Array.from(list || []);
                if (!fileList.length) return;

                // si multiple=false, on remplace la liste (sauf si on veut conserver existants — ici on remplace non-existing)
                if (!this.multiple) {
                    // supprimer toutes les non-existantes (on remplace)
                    this.files = this.files.filter(f => f.isExisting);
                }

                fileList.forEach((file, i) => {
                    // si non autorisé, ignore
                    if (this.accept !== '*' && !file.type.match(this.accept.replace('*','.*'))) {
                        return;
                    }

                    const isImage = file.type.startsWith('image/');
                    const preview = isImage ? URL.createObjectURL(file) : null;
                    const readerSize = file.size;
                    this.files.push({
                        key: 'new-' + (Date.now() + Math.random()),
                        file: file,
                        name: file.name,
                        preview: preview,
                        size: readerSize,
                        isImage: isImage,
                        isExisting: false,
                        humanSize: this.formatBytes(readerSize)
                    });
                });

                // mettre à jour l'input file (programmatique)
                this.syncNativeInput();
            },

            onDrop(event) {
                this.dragOver = false;
                const dt = event.dataTransfer;
                if (dt && dt.files && dt.files.length) {
                    this.handleFiles(dt.files);
                }
            },

            remove(index) {
                const f = this.files[index];
                if (!f) return;
                // libérer objectURL si nécessaire
                if (f.preview && !f.isExisting) {
                    try { URL.revokeObjectURL(f.preview); } catch(e) {}
                }
                this.files.splice(index, 1);
                this.syncNativeInput();
            },

            // prépare un DataTransfer pour peupler l'input file (pour que form submit envoie les fichiers)
            syncNativeInput() {
                // On ne peut pas assigner FileList directement; on construit un DataTransfer
                const dt = new DataTransfer();
                this.files.forEach(f => {
                    if (!f.isExisting && f.file) {
                        dt.items.add(f.file);
                    }
                });
                // si multiple === false et dt.files.length > 1, garde le premier
                if (!this.multiple && dt.files.length > 1) {
                    // rien de spécial — le navigateur gérera selon name (mais on choisit ici le premier)
                    const first = dt.files[0];
                    const dt2 = new DataTransfer();
                    dt2.items.add(first);
                    this.$refs.input.files = dt2.files;
                } else {
                    this.$refs.input.files = dt.files;
                }
            },

            formatBytes(bytes, decimals = 1) {
                if (bytes === 0 || bytes == null) return '';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }
        }
    }
</script>