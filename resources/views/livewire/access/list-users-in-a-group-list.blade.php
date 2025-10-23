<div class="p-4">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            {{ __('Groups & Users List') }}
        </h1>
    </div>

    {{-- Search --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div class="relative w-full sm:w-96">
            <input
                type="text"
                wire:model.defer="search"
                placeholder="{{ __('Search group or user...') }}"
                class="border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 w-full pr-32
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition"
            >

            <div wire:loading wire:target="search" class="absolute inset-y-0 right-16 flex items-center pr-3">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </div>

            <button
                wire:click="applySearch"
                class="absolute inset-y-0 right-0 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-md text-sm font-medium transition"
            >
                {{ __('Search') }}
            </button>
        </div>
    </div>

    {{-- Table --}}
    <x-table.card 
        :columns="[
            ['label' => '#', 'align' => 'center'],
            ['label' => __('Group Name'), 'align' => 'center'],
            ['label' => __('Users count'), 'align' => 'center'],
            ['label' => __('Role'), 'align' => 'center'],
            ['label' => __('Status'), 'align' => 'center'],
            ['label' => __('Actions'), 'align' => 'center'],
        ]"
        :notice="__('PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')"
    >
        @forelse($groups as $group)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 text-sm text-center">{{ $group->id }}</td>

                <td class="px-6 py-4 text-sm text-center font-medium">
                    {{ $group->name }}
                </td>

                <td class="px-6 py-4 text-sm text-center">
                    {{ $group->users->count() }}
                </td>

                <td class="px-6 py-4 text-sm text-center">
                    {{ $group->role->roleName ?? 'N/A' }}
                </td>

                <td class="px-6 py-4 text-sm text-center">
                    <span class="{{ $group->is_active ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $group->is_active ? __('Active') : __('Inactive') }}
                    </span>
                </td>

                <td class="px-6 py-4 text-center">
                    <button wire:click="openGroupUsersModal({{ $group->id }})"
                            class="px-3 py-1 text-sm font-medium border border-blue-600 text-blue-600 rounded hover:bg-blue-50 dark:hover:bg-blue-900">
                        {{ __('View Users') }}
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    {{ __('No groups or users found.') }}
                </td>
            </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $groups->links() }}
        </x-slot>
    </x-table.card>

    {{-- Modal Users in Group --}}
    @if($showModal && $selectedGroup)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg shadow-lg">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Users in group:') }} {{ $selectedGroup->name }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($selectedGroup->users as $user)
                        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 pb-3">
                            {{-- Avatar --}}
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}"
                                    alt="Photo de profil de {{ $user->name }}"
                                    class="w-10 h-10 rounded-full object-cover border border-gray-300 dark:border-gray-600 flex-shrink-0"
                                    loading="lazy">
                            @else
                                {{-- Si pas d'avatar, afficher initiales --}}
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-700 text-gray-800 dark:text-gray-100 font-semibold text-base flex-shrink-0"
                                    aria-label="Avatar de {{ $user->name }}">
                                    {{ Str::of($user->name)->substr(0, 1)->upper() }}
                                </div>
                            @endif
                            
                            {{-- Informations utilisateur --}}
                            <div class="flex flex-col min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                    {{ $user->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">{{ __('No users in this group.') }}</p>
                    @endforelse
                </div>

                <div class="flex justify-end p-6 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
