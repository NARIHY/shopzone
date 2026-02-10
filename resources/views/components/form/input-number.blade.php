@props([
    'id',
    'name',
    'label' => null,
    'value' => '',
    'help' => null,
    'size' => 'table' // normal | table
])

@php
    $isTable = $size === 'table';

    $baseClass = $isTable
        ? 'border rounded w-8 h-8 text-sm text-center
           bg-white dark:bg-gray-700
           text-gray-900 dark:text-gray-100
           border-gray-300 dark:border-gray-600
           focus:outline-none focus:ring-1 focus:ring-blue-500'
        : 'border rounded px-3 py-2 w-full
           bg-gray-50 dark:bg-gray-700
           text-gray-900 dark:text-gray-100
           border-gray-300 dark:border-gray-600
           focus:outline-none focus:ring-2 focus:ring-blue-500';
@endphp

<div class="{{ $isTable ? '' : 'flex flex-col gap-1' }}">
    
    {{-- Label seulement hors tableau --}}
    @if($label && !$isTable)
        <label for="{{ $id ?? $name }}"
               class="text-gray-700 dark:text-gray-200 font-medium">
            {{ $label }}
        </label>
    @endif

    <input
        type="text"
        maxlength="1"
        inputmode="numeric"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge([
            'class' => $baseClass
        ]) }}
    />

    {{-- Help --}}
    @if($help && !$isTable)
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif

    {{-- Erreur --}}
    @error($name)
        <span class="text-red-500 text-xs">{{ $message }}</span>
    @enderror
</div>
