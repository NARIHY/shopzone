<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{-- Flash message --}}
            @if(session()->has('success'))
            <div class="mb-4 rounded bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 px-4 py-2 text-green-800 dark:text-green-100">
                {{ session('success') }}
                <button type="button" class="ml-2 text-green-800 dark:text-green-100" onclick="this.parentElement.style.display='none';">×</button>
            </div>
            @endif

            @if(session()->has('error'))
            <div class="mb-4 rounded bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 px-4 py-2 text-red-800 dark:text-red-100">
                {{ session('error') }}
                <button type="button" class="ml-2 text-red-800 dark:text-red-100" onclick="this.parentElement.style.display='none';">×</button>
            </div>
            @endif

            @if(session()->has('warning'))
            <div class="mb-4 rounded bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 px-4 py-2 text-yellow-800 dark:text-yellow-100">
                {{ session('warning') }}
                <button type="button" class="ml-2 text-yellow-800 dark:text-yellow-100" onclick="this.parentElement.style.display='none';">×</button>
            </div>
            @endif
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
