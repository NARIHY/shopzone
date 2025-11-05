<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>

        {{ $slot }}

    </flux:main>

    <div class="fixed bottom-4 right-4">
        <x-utils.appearances-bottom />
    </div>
</x-layouts.app.sidebar>
