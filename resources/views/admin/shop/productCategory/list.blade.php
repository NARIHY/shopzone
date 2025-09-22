{{-- Example of useing body content --}}
<x-layouts.app :title="__('List Product Categories')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:shop.product-category-list />
    </div>
</x-layouts.app>
