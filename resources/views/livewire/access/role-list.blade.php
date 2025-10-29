<div class="p-4">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            {{ __('Roles List') }}
        </h1>
    </div>

    {{-- Search --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="relative w-full sm:w-96">
        <!-- Input with embedded button -->
        <input
            type="text"
            wire:model.defer="search"
            placeholder="{{ __('Search product...') }}"
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

    <!-- New Category Button -->
    <a href="{{ route('admin.roles.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap transition">
        {{ __('shop.+ New Role') }}
    </a>
</div>

    {{-- Table --}}
    <x-table.card 
        :columns="[
            ['label' => '#', 'align' => 'left'],
            ['label' => __('Role Name'), 'align' => 'center'],
            ['label' => __('Status'), 'align' => 'center'],
            ['label' => __('Actions'), 'align' => 'center'],
        ]"
        :notice="__('PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')"
    >
        @forelse($roles as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                {{-- ID --}}
                <td class="px-6 py-4 text-sm text-center align-middle">{{ $item->id }}</td>

                {{-- Role Name --}}
                <td class="px-6 py-4 text-sm text-center align-middle">
                    {{ $item->roleName ?? 'N/A' }}
                </td>

                {{-- Status --}}
                <td class="px-6 py-4 text-sm text-center align-middle">
                   <div class="text-sm font-medium {{ $item->is_active != 0 ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $item->is_active != 0 ? __('Active') : __('Inactive') }}
                    </div>
                </td>

                {{-- Actions --}}
                <td class="px-6 py-4 text-center align-middle">
                    <div class="flex flex-col sm:flex-row justify-center gap-2">
                        <button wire:click="openRoleModal({{ $item->id }})"
                                class="px-3 py-1 text-sm font-medium border border-blue-600 text-blue-600 rounded hover:bg-blue-50 dark:hover:bg-blue-900">
                            {{ __('Details') }}
                        </button>

                        <a href="{{ route('admin.roles.edit', $item )}}" class="px-3 py-1 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700">
                            {{ __('edit') }}
                        </a>
                        {{-- UNCOMMENT FOR allow delet --}}

                        {{-- <button
                            onclick="if(!confirm('{{ __('Are you sure you want to delete this file?') }}')) return event.stopImmediatePropagation();"
                            wire:click="deleteMedia({{ $item->id }})"
                            class="px-3 py-1 text-sm font-medium border border-red-600 text-red-600 rounded hover:bg-red-50 dark:hover:bg-red-900">
                            {{ __('Delete') }}
                        </button> --}}
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    {{ __('No roles found.') }}
                </td>
            </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $roles->links() }}
        </x-slot>
    </x-table.card>

    {{-- Modal détails --}}
    @if($showModal && $selectedRole)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4" role="dialog" aria-modal="true" aria-labelledby="role-modal-title">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg overflow-hidden shadow-lg" role="document">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 id="role-modal-title" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $selectedRole->roleName ?? 'No role name' }}
                    </h2>
                    <button wire:click="closeModal" aria-label="{{ __('Close') }}" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <p><strong>{{ __('Role Name') }}: </strong> {{ $selectedRole->roleName ?? 'N/A' }}</p>
                    <p><strong>{{ __('Description') }}: </strong> {{ $selectedRole->description ?? 'N/A' }}</p>
                    <p><strong>{{ __('Status') }}: </strong> 
                        {{ $selectedRole->is_active ? __('Active') : __('Inactive') }}
                    </p>
                    <p><strong>{{ __('Created at') }}: </strong> {{ $selectedRole->created_at?->format('d/m/Y H:i') }}</p>
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
