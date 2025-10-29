<div class="p-4">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            {{ __('Contacts List') }}
        </h1>
    </div>

    {{-- Search + Upload --}}

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="relative w-full sm:w-96">
        <!-- Input with embedded button -->
        <input
            type="text"
            wire:model.defer="search"
            placeholder="{{ __('Search contact...') }}"
            class="w-full rounded-xl border border-gray-300 dark:border-gray-700 
                   bg-white dark:bg-gray-900 px-4 py-2.5 pr-28
                   text-sm text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                   placeholder:text-gray-400 dark:placeholder:text-gray-500 transition"
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
            class="absolute inset-y-0 right-0 bg-blue-600 hover:bg-blue-700 
                   text-white text-sm font-medium rounded-r-xl px-4 transition flex items-center justify-center"
        >
        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            {{ __('Search') }}
        </button>
    </div>

</div>

    {{-- Table --}}
    <x-table.card 
        :columns="[
            ['label' => '#', 'align' => 'left'],
            ['label' => __('Sender'), 'align' => 'center'],
            ['label'=> __('Subject'), 'align' => 'center'],
            ['label' => __('Actions'), 'align' => 'center'],
        ]"
        :notice="__('PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')"
    >
        @forelse($contacts as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                {{-- ID --}}
                <td class="px-6 py-4 text-sm text-center align-middle">{{ $item->id }}</td>

                {{-- Sender --}}
                <td class="px-6 py-4 text-sm text-center align-middle">
                    {{ $item->firstname  ?? 'N/A' }} {{ $item->lastname ?? 'N/A'  }}
                </td>

                {{-- subject --}}
                <td class="px-6 py-4 text-sm text-center align-middle">
                    {{ $item->subject  ?? 'N/A' }}
                </td>

                {{-- Actions --}}
                <td class="px-6 py-4 text-center align-middle">
                    <div class="flex flex-col sm:flex-row justify-center gap-2">
                        <button wire:click="openMediaModal({{ $item->id }})"
                                class="px-3 py-1 text-sm font-medium border border-blue-600 text-blue-600 rounded hover:bg-blue-50 dark:hover:bg-blue-900">
                            {{ __('Details') }}
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    {{ __('No contact found.') }}
                </td>
            </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $contacts->links() }}
        </x-slot>
    </x-table.card>

    {{-- Modal détails --}}
    @if($showModal && $selectedContact)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4" role="dialog" aria-modal="true" aria-labelledby="media-modal-title">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg overflow-hidden shadow-lg" role="document">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 id="media-modal-title" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $selectedContact->subject ?? 'No subject' }}
                    </h2>
                    <button wire:click="closeModal" aria-label="{{ __('Close') }}" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

                <div class="p-6 space-y-4">
    <p><strong>{{ __('fristName') }}: </strong> {{ $selectedContact->firstname ?? 'N/A' }}</p>
    <p><strong>{{ __('lastName') }}: </strong> {{ $selectedContact->lastname ?? 'N/A' }}</p>
    <p>
        <strong>{{ __('Email') }}: </strong>
        <a href="mailto:{{ $selectedContact->email ?? 'N/A' }}" class="text-blue-600 hover:underline">
            {{ $selectedContact->email ?? 'N/A' }}
        </a>
    </p>
    <p><strong>{{ __('Uploaded at') }}: </strong> {{ $selectedContact->created_at?->format('d/m/Y H:i') }}</p>

    {{-- Message en lecture seule (sélectionnable) --}}
    <div>
        <label for="contact-message" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
            {{ __('Message') }}
        </label>

        <textarea
            id="contact-message"
            readonly
            aria-readonly="true"
            rows="6"
            class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 resize-y focus:outline-none"
        >{{ $selectedContact->message ?? 'N/A' }}</textarea>
    </div>
</div>


                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
