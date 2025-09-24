@props([
    'id',
    'name',
    'label' => null,
    'type' => 'text',
    'value' => '',
])

<div class="flex flex-col gap-1">
    @if($label)
        <label for="{{ $id ?? $name }}"
               class="text-gray-700 dark:text-gray-200 font-medium">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge([
            'class' => 'border rounded px-3 py-2 w-full
                        bg-gray-50 dark:bg-gray-700
                        text-gray-900 dark:text-gray-100
                        border-gray-300 dark:border-gray-600
                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent'
        ]) }}
    />

    @error($name)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
