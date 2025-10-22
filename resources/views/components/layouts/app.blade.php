<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>

        {{ $slot }}

        <!-- Toast Notifications en bas à droite -->
        <div class="fixed bottom-5 right-5 space-y-2 z-50">
            @foreach (['success', 'error', 'warning'] as $type)
                @if(session()->has($type))
                    @php
                        $colors = [
                            'success' => 'green-500',
                            'error' => 'red-500',
                            'warning' => 'yellow-500',
                        ];
                        $color = $colors[$type] ?? 'gray-500';
                    @endphp

                    <div 
                        x-data="{ show: true }" 
                        x-show="show" 
                        x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-4"
                        class="bg-{{ $color }} text-white px-4 py-3 rounded shadow-lg flex items-center justify-between"
                    >
                        <span>{{ session($type) }}</span>
                        <button @click="show = false" class="ml-4 font-bold text-xl">&times;</button>
                    </div>
                @endif
            @endforeach
        </div>

    </flux:main>

    <div class="fixed bottom-4 right-4">
        <x-utils.appearances-bottom />
    </div>
</x-layouts.app.sidebar>
