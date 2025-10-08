@props([
    'id' => Str::random(8),
    'name' => 'select',
    'options' => [],
    'placeholder' => 'Sélectionnez une option',
    'selected' => null,
    'help' => null,
])

<div x-data="{
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
        select(value) {
            this.selected = value;
            this.search = this.options[value];
            this.open = false;
        }
    }" 
    x-init="if(selected) search = options[selected]" 
    class="relative w-full">

    <input 
        type="text" 
        placeholder="{{ $placeholder }}" 
        x-model="search"
        @focus="open = true" 
        @keydown.escape="open = false"
        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:ring focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-200"
    >

    @if($help)
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif

    @error($name)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
    <input type="hidden" name="{{ $name }}" :value="selected">

    <div 
        x-show="open && filtered.length" 
        x-transition 
        class="absolute z-20 mt-1 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-lg max-h-48 overflow-auto"
    >
        <template x-for="[value, label] in filtered" :key="value">
            <div 
                @click="select(value)" 
                class="px-3 py-2 text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-800"
                :class="{'bg-indigo-100 dark:bg-indigo-700': value === selected}"
            >
                <span x-text="label"></span>
            </div>
        </template>
    </div>

</div>
