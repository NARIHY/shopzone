{{-- Example of useing body content --}}
<x-layouts.app :title="__('Mail Can Client Sends List')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- livewire layout --}}
        <livewire:mail.mail-can-send-list.mail-can-send-list />
    </div>
</x-layouts.app>
