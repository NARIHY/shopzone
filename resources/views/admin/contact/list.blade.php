{{-- Example of useing body content --}}
<x-layouts.app :title="__('Contact List')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- CONTENT HERE --}}
        <livewire:contact.contact-list-admin />
    </div>
</x-layouts.app>
