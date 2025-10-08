{{-- Example of useing body content --}}
<x-layouts.app :title="__('Show Product')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- CONTENT --}}
        <livewire:shop.product-list />
    </div>
</x-layouts.app>
