{{-- Example of useing body content --}}
<x-layouts.app :title="__('All Permission List')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:access.permission-list />
        {{-- CONTENT HERE --}}
    </div>
</x-layouts.app>
