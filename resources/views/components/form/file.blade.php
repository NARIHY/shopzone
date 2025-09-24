@props([
    'id',
    'name',
    'label' => null,
    'multiple' => false,
])

<div class="flex flex-col gap-1">
    @if($label)
        <label for="{{ $id ?? $name }}"
               class="text-gray-700 dark:text-gray-200 font-medium">
            {{ $label }}
        </label>
    @endif

    <input
        type="file"
        name="{{ $multiple ? $name.'[]' : $name }}"
        id="{{ $id ?? $name }}"
        @if($multiple) multiple @endif
        {{ $attributes->merge([
            'class' => 'block w-full text-sm text-gray-900 dark:text-gray-100
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100
                        dark:file:bg-blue-900 dark:file:text-blue-100
                        dark:hover:file:bg-blue-800
                        cursor-pointer'
        ]) }}
    />

    @error($name)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
