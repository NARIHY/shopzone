<div class="p-4">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            {{ __('Media Library') }}
        </h1>
    </div>

    {{-- Search + Upload --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div class="relative w-full sm:w-80">
            <input type="text" wire:model="search"
                placeholder="{{ __('Search media...') }}"
                class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 w-full 
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
        </div>

        <a href="{{ route('admin.media.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap">
            {{ __('+ Upload Media') }}
        </a>
    </div>

    {{-- Table --}}
    <x-table.card 
        :columns="[
            ['label' => '#', 'align' => 'left'],
            ['label' => __('Title'), 'align' => 'center'],
            ['label'=> __('Size'), 'align' => 'center'],
            ['label' => __('Actions'), 'align' => 'center'],
        ]"
        :notice="__('PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')"
    >
        @forelse($media as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 text-sm">{{ $item->id }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->title ?? $item->original_name }}</td>
                <td class="px-6 py-4 text-sm">{{ number_format($item->size / 1024, 2) }} KB</td>
                <td class="px-6 py-4 text-center">
                    <div class="flex flex-col sm:flex-row justify-center gap-2">
                        <a href="{{ $item->url() }}" target="_blank"
                           class="px-3 py-1 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700">
                            {{ __('View') }}
                        </a>
                        <button wire:click="openMediaModal({{ $item->id }})"
                                class="px-3 py-1 text-sm font-medium border border-blue-600 text-blue-600 rounded hover:bg-blue-50 dark:hover:bg-blue-900">
                            {{ __('Details') }}
                        </button>
                        <button
                            onclick="if(!confirm('{{ __('Are you sure you want to delete this file?') }}')) return event.stopImmediatePropagation();"
                            wire:click="deleteMedia({{ $item->id }})"
                            class="px-3 py-1 text-sm font-medium border border-red-600 text-red-600 rounded hover:bg-red-50 dark:hover:bg-red-900">
                            {{ __('Delete') }}
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    {{ __('No media found.') }}
                </td>
            </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $media->links() }}
        </x-slot>
    </x-table.card>

    {{-- Modal détails --}}
    @if($showModal && $selectedMedia)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $selectedMedia->title ?? $selectedMedia->original_name }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <p><strong>ID:</strong> {{ $selectedMedia->id }}</p>
                    <p><strong>{{ __('Original name') }}:</strong> {{ $selectedMedia->original_name }}</p>
                    <p><strong>{{ __('Mime') }}:</strong> {{ $selectedMedia->mime_type }}</p>
                    <p><strong>{{ __('Size') }}:</strong> {{ number_format($selectedMedia->size / 1024, 2) }} KB</p>
                    <p><strong>{{ __('Uploaded at') }}:</strong> {{ $selectedMedia->created_at?->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ $selectedMedia->url() }}" target="_blank"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                        {{ __('Open') }}
                    </a>
                    <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
