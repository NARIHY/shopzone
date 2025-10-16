<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{-- Flash messages --}}
        @foreach (['success', 'error', 'warning'] as $type)
            @if(session()->has($type))
                @php
                    $colors = [
                        'success' => ['bg' => 'green-50', 'darkBg' => 'green-900', 'border' => 'green-200', 'darkBorder' => 'green-700', 'text' => 'green-800', 'darkText' => 'green-100', 'icon' => '✓'],
                        'error' => ['bg' => 'red-50', 'darkBg' => 'red-900', 'border' => 'red-200', 'darkBorder' => 'red-700', 'text' => 'red-800', 'darkText' => 'red-100', 'icon' => '✕'],
                        'warning' => ['bg' => 'yellow-50', 'darkBg' => 'yellow-900', 'border' => 'yellow-200', 'darkBorder' => 'yellow-700', 'text' => 'yellow-800', 'darkText' => 'yellow-100', 'icon' => '⚠'],
                    ];
                    $color = $colors[$type];
                @endphp

                <div class="mb-4 flex items-center justify-between rounded-lg border shadow-sm 
                            bg-{{ $color['bg'] }} dark:bg-{{ $color['darkBg'] }} 
                            border-{{ $color['border'] }} dark:border-{{ $color['darkBorder'] }} 
                            px-4 py-3 text-sm font-medium 
                            text-{{ $color['text'] }} dark:text-{{ $color['darkText'] }}">

                    <div class="flex items-center gap-2">
                        <span class="text-lg">{{ $color['icon'] }}</span>
                        <span>{{ session($type) }}</span>
                    </div>

                    <button type="button" class="ml-4 rounded-full px-2 py-1 text-xl font-bold 
                               hover:bg-{{ $color['border'] }}/30 dark:hover:bg-{{ $color['darkBorder'] }}/50 
                               transition" onclick="this.parentElement.style.display='none';">
                        ×
                    </button>
                </div>
            @endif
        @endforeach

        

        {{ $slot }}
    </flux:main>
    <div class="fixed bottom-4 right-4" style="right: 1rem; bottom: 1rem;">
        <x-utils.appearances-bottom />
    </div>
</x-layouts.app.sidebar>