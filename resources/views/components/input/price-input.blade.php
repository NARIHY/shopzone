@props([
    'name',
    'label' => null,
    'value' => null,
    'help' => null,
])

<div class="w-full space-y-1">
    {{-- Label facultatif --}}
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
        </label>
    @endif

    <div 
        x-data="{
            rawValue: {{ $value ?? 0 }},
            displayValue() {
                return this.rawValue.toLocaleString('fr-FR');
            }
        }"
        class="relative flex items-center"
    >
        {{-- Champ visible type number --}}
        <input
            id="{{ $name }}"
            type="number"
            x-model.number="rawValue"
            name="{{ $name }}"
            min="0"
            step="1"
            placeholder="0"
            class="w-full text-right border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 pr-16 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        />

        {{-- Affichage formaté avec suffixe Ar --}}
        <span class="absolute right-4 text-gray-500 dark:text-gray-400 font-medium select-none" x-text="'Ar'"></span>

        {{-- Valeur brute cachée --}}
        <input type="hidden" :value="rawValue" name="{{ $name }}_raw">
    </div>

    {{-- Message d’aide facultatif --}}
    @if($help)
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif

    {{-- Message d’erreur --}}
    @error($name)
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
