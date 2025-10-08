@props([
    'name',
    'label' => null,
    'value' => null,
    'min' => null,
    'max' => null,
    'step' => 1,
    'help' => null,
    'suffix' => null,
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
            value: '{{ $value ?? '' }}',
        }"
        class="relative flex items-center"
    >
        {{-- Input principal --}}
        <input
            id="{{ $name }}"
            type="number"
            x-model="value"
            name="{{ $name }}"
            min="{{ $min }}"
            max="{{ $max }}"
            step="{{ $step }}"
            placeholder="0"
            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 pr-10 text-right bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        />

        {{-- Suffixe facultatif --}}
        @if($suffix)
            <span class="absolute right-4 text-gray-500 dark:text-gray-400 font-medium select-none">
                {{ $suffix }}
            </span>
        @endif
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
