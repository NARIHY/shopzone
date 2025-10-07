<div class="p-4">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            {{ __('Groups Users List') }}
        </h1>
    </div>

    {{-- Search --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div class="relative w-full sm:w-80">
            <input type="text" wire:model.debounce.300ms="search"
                placeholder="{{ __('Search roles...') }}"
                class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 w-full 
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                aria-label="{{ __('Search groups') }}">
        </div>

        <a href="{{ route('admin.groups.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap">
            {{ __('+New group') }}
        </a>
    </div>

    {{-- Table --}}
    <x-table.card 
        :columns="[
            ['label' => '#', 'align' => 'left'],
            ['label' => __('Group Name'), 'align' => 'center'],
            ['label' => __('Status'), 'align' => 'center'],
            ['label' => __('Roles'), 'align' => 'center'],
            ['label' => __('Actions'), 'align' => 'center'],
        ]"
        :notice="__('PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')"
    >
        @forelse($groups as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                {{-- ID --}}
                <td class="px-6 py-4 text-sm text-center align-middle">{{ $item->id }}</td>

                {{-- Group Name --}}
                <td class="px-6 py-4 text-sm text-center align-middle">
                    {{ $item->name ?? 'N/A' }}
                </td>

                {{-- Status --}}
                <td class="px-6 py-4 text-sm text-center align-middle">
                   <div class="text-sm font-medium {{ $item->is_active != 0 ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $item->is_active != 0 ? __('Active') : __('Inactive') }}
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-center align-middle">
                    {{ $item->role->roleName ?? 'N/A' }}
                </td>

                {{-- Actions --}}
                <td class="px-6 py-4 text-center align-middle">
                    <div class="flex flex-col sm:flex-row justify-center gap-2">
                        <button wire:click="openRoleModal({{ $item->id }})"
                                class="px-3 py-1 text-sm font-medium border border-blue-600 text-blue-600 rounded hover:bg-blue-50 dark:hover:bg-blue-900">
                            {{ __('Details') }}
                        </button>

                        <a href="{{ route('admin.groups.edit', $item )}}" class="px-3 py-1 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700">
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
                    {{ __('No groups users found.') }}
                </td>
            </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $groups->links() }}
        </x-slot>
    </x-table.card>

    {{-- Modal détails --}}
    @if($showModal && $selectedGroup)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4" role="dialog" aria-modal="true" aria-labelledby="role-modal-title">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg overflow-hidden shadow-lg" role="document">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 id="role-modal-title" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $selectedGroup->name ?? 'No role name' }}
                    </h2>
                    <button wire:click="closeModal" aria-label="{{ __('Close') }}" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <p><strong>{{ __('Group Name') }}: </strong> {{ $selectedGroup->roleName ?? 'N/A' }}</p>
                    <p><strong>{{ __('Description') }}: </strong> {{ $selectedGroup->description ?? 'N/A' }}</p>
                    <p><strong>{{ __('Status') }}: </strong> 
                        {{ $selectedGroup->is_active ? __('Active') : __('Inactive') }}
                    </p>
                    <p><strong>
                        {{ __('Roles') }}: 
                        </strong>
                        {{ $selectedGroup->role->roleName ?? 'N/A' }}
                    </p>
                    <p><strong>{{ __('Created at') }}: </strong> {{ $selectedGroup->created_at?->format('d/m/Y H:i') }}</p>
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
