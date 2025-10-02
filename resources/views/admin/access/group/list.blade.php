{{-- Example of useing body content --}}
<x-layouts.app :title="__('Group User List')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- CONTENT HERE --}}
        <livewire:access.group-list />
    </div>
</x-layouts.app>
