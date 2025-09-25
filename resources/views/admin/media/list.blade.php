{{-- Example of useing body content --}}
<x-layouts.app :title="__('Media List')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:media.media-list />
    </div>
</x-layouts.app>
