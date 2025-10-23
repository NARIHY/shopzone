{{-- Example of useing body content --}}
<x-layouts.app :title="__('Affect User By Group List')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:access.list-users-in-agroup-list />
        {{-- CONTENT HERE --}}
    </div>
</x-layouts.app>
