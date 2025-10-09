<div class="p-4">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            {{ __('Media Library') }}
        </h1>
    </div>

    {{-- Search + Upload --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div class="relative w-full sm:w-96">
        <!-- Input with embedded button -->
        <input
            type="text"
            wire:model.defer="search"
            placeholder="{{ __('Search media...') }}"
            class="border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 w-full pr-32
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                   bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition"
        >

        <!-- Loader -->
        <div wire:loading wire:target="search" class="absolute inset-y-0 right-16 flex items-center pr-3">
            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        </div>

        <!-- Search Button inside input -->
        <button
            wire:click="applySearch"
            class="absolute inset-y-0 right-0 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-md text-sm font-medium transition"
        >
            {{ __('Search') }}
        </button>
    </div>

    <!-- New Category Button -->
    <a href="{{ route('admin.media.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap transition">
        {{ __('shop.+ New Media') }}
    </a>
</div>

    {{-- Table --}}
    <x-table.card 
        :columns="[
            ['label' => '#', 'align' => 'left'],
            ['label' => __('Title'), 'align' => 'center'],
            ['label'=> __('Media'), 'align' => 'center'],
            ['label'=> __('Size'), 'align' => 'center'],
            ['label' => __('Actions'), 'align' => 'center'],
        ]"
        :notice="__('PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')"
    >
        @forelse($media as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                {{-- ID --}}
                <td class="px-6 py-4 text-sm text-center align-middle">{{ $item->id }}</td>

                {{-- Title --}}
                <td class="px-6 py-4 text-sm text-center align-middle">
                    {{ $item->title ?? $item->original_name }}
                </td>

                {{-- Media preview --}}
                <td class="px-6 py-4 text-sm text-center align-middle">
                    @if(optional($item)->mime_type && Str::startsWith($item->mime_type, 'image/'))
                        <a href="{{ $item->url() }}" target="_blank" rel="noopener" class="inline-block">
                            <img src="{{ $item->url() }}" alt="{{ $item->title ?? $item->original_name }}" class="h-12 w-12 object-cover rounded mx-auto" />
                        </a>
                    @else
                        {{-- Non-image: show file extension badge --}}
                        <div class="inline-flex items-center justify-center h-12 w-12 rounded bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-700 dark:text-gray-100 mx-auto">
                            {{ strtoupper(pathinfo($item->original_name ?? '', PATHINFO_EXTENSION) ?: 'FILE') }}
                        </div>
                    @endif
                </td>

                {{-- Size (human readable) --}}
                <td class="px-6 py-4 text-sm text-center align-middle">
                    @php
                        $size = $item->size ?? 0;
                        if ($size >= 1024 * 1024) {
                            $sizeFormatted = number_format($size / 1024 / 1024, 2) . ' MB';
                        } elseif ($size >= 1024) {
                            $sizeFormatted = number_format($size / 1024, 2) . ' KB';
                        } else {
                            $sizeFormatted = $size . ' B';
                        }
                    @endphp
                    {{ $sizeFormatted }}
                </td>

                {{-- Actions --}}
                <td class="px-6 py-4 text-center align-middle">
                    <div class="flex flex-col sm:flex-row justify-center gap-2">
                        <a href="{{ route('admin.media.edit', $item )}}" class="px-3 py-1 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700">
                            {{ __('edit') }}
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
                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
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
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4" role="dialog" aria-modal="true" aria-labelledby="media-modal-title">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg overflow-hidden shadow-lg" role="document">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 id="media-modal-title" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $selectedMedia->title ?? $selectedMedia->original_name }}
                    </h2>
                    <button wire:click="closeModal" aria-label="{{ __('Close') }}" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    {{-- Preview --}}
                    @if(optional($selectedMedia)->mime_type && Str::startsWith($selectedMedia->mime_type, 'image/'))
                        <div class="w-full flex justify-center">
                            <img src="{{ $selectedMedia->url() }}" alt="{{ $selectedMedia->title ?? $selectedMedia->original_name }}" class="max-h-64 object-contain rounded" />
                        </div>
                    @else
                        <div class="w-full flex justify-center">
                            <div class="w-32 h-32 flex items-center justify-center rounded bg-gray-100 dark:bg-gray-700 text-sm font-medium">
                                {{ strtoupper(pathinfo($selectedMedia->original_name ?? '', PATHINFO_EXTENSION) ?: 'FILE') }}
                            </div>
                        </div>
                    @endif

                    <p><strong>ID:</strong> {{ $selectedMedia->id }}</p>
                    <p><strong>{{ __('Original name') }}:</strong> {{ $selectedMedia->original_name }}</p>
                    <p><strong>{{ __('Mime') }}:</strong> {{ $selectedMedia->mime_type }}</p>
                    <p><strong>{{ __('Size') }}:</strong>
                        @php
                            $s = $selectedMedia->size ?? 0;
                            if ($s >= 1024 * 1024) { $sFmt = number_format($s / 1024 / 1024, 2) . ' MB'; }
                            elseif ($s >= 1024) { $sFmt = number_format($s / 1024, 2) . ' KB'; }
                            else { $sFmt = $s . ' B'; }
                        @endphp
                        {{ $sFmt }}
                    </p>
                    <p><strong>{{ __('Uploaded at') }}:</strong> {{ $selectedMedia->created_at?->format('d/m/Y H:i') }}</p>
                </div>

                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ $selectedMedia->url() }}" target="_blank" rel="noopener"
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
