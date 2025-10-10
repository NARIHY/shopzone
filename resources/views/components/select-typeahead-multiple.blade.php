@props([
    'id' => Str::random(8),
    'name' => 'select',
    'options' => [],
    'placeholder' => 'Sélectionnez des options',
    'selected' => [],
    'help' => null,
])

<div
    x-data="{
        open: false,
        search: '',
        selected: @js($selected),
        options: @js($options),

        get filtered() {
            if (!this.search) return Object.entries(this.options);
            return Object.entries(this.options).filter(([value, label]) =>
                label.toLowerCase().includes(this.search.toLowerCase())
            );
        },

        toggle(value) {
            if (this.selected.includes(value)) {
                this.selected = this.selected.filter(v => v !== value);
            } else {
                this.selected.push(value);
            }
            this.search = '';
        },

        remove(value) {
            this.selected = this.selected.filter(v => v !== value);
        },

        isSelected(value) {
            return this.selected.includes(value);
        }
    }"
    class="relative w-full"
>
    {{-- Tags + Search --}}
    <div 
        @click="open = true"
        class="flex flex-wrap items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-800 cursor-text focus-within:ring focus-within:ring-indigo-500"
    >
        <template x-for="value in selected" :key="value">
            <div class="flex items-center bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-100 px-2 py-1 rounded-md text-xs">
                <span x-text="options[value]"></span>
                <button type="button" @click.stop="remove(value)" class="ml-1 text-indigo-500 hover:text-indigo-800 dark:hover:text-white">
                    &times;
                </button>
            </div>
        </template>

        <input
            type="text"
            x-model="search"
            placeholder="{{ $placeholder }}"
            @focus="open = true"
            @keydown.escape="open = false"
            class="flex-1 bg-transparent border-none text-sm focus:outline-none dark:text-gray-200"
        >
    </div>

    {{-- Hidden input --}}
    <input type="hidden" name="{{ $name }}" :value="JSON.stringify(selected)">

    {{-- Help text --}}
    @if($help)
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $help }}</p>
    @endif

    {{-- Error --}}
    @error($name)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror

    {{-- Dropdown --}}
    <div
        x-show="open && filtered.length"
        x-transition
        @click.outside="open = false"
        class="absolute z-20 mt-1 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-lg max-h-48 overflow-auto"
    >
        <template x-for="[value, label] in filtered" :key="value">
            <div
                @click="toggle(value)"
                class="px-3 py-2 text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-800"
                :class="{'bg-indigo-100 dark:bg-indigo-700': isSelected(value)}"
            >
                <span x-text="label"></span>
                <span x-show="isSelected(value)" class="float-right text-indigo-500 font-semibold">✓</span>
            </div>
        </template>
    </div>
</div>
