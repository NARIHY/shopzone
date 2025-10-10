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
            displayValue: '{{ number_format($value ?? 0, 0, ',', ' ') }}',
            rawValue: {{ $value ?? 0 }},
            formatPrice() {
                let num = this.displayValue.replace(/\D/g, '');
                this.rawValue = parseInt(num || 0);
                this.displayValue = new Intl.NumberFormat('fr-FR').format(this.rawValue);
            }
        }"
        x-init="formatPrice()"
        class="relative flex items-center"
    >
        {{-- Champ visible --}}
        <input
    id="{{ $name }}"
    type="text"                {{-- reste text pour le formatage --}}
    x-model="displayValue"
    x-on:input.debounce.150ms="formatPrice()"
    x-on:blur="formatPrice()"
    inputmode="numeric"
    placeholder="0"
    class="w-full text-right border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 pr-10 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
/>



        {{-- Suffixe de devise --}}
        <span class="absolute right-4 text-gray-500 dark:text-gray-400 font-medium select-none">Ar</span>

        {{-- Valeur numérique brute cachée --}}
        <input type="hidden" :value="rawValue" name="{{ $name }}">
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
