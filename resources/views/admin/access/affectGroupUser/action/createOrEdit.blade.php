<x-layouts.app :title="__('Edit or Create User-Group Assignment')">

    {{-- Page Title --}}
    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ isset($affectUserToGroup) ? __('Edit User-Group Assignment') : __('Create User-Group Assignment') }}
        </h1>

        <nav>
            <ol class="breadcrumb flex space-x-2 text-gray-600 dark:text-gray-400">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Dashboard') }}
                    </a>
                </li>
                <li>/</li>
                <li>
                    <a href="{{ route('admin.groupUsers.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('User ↔ Group Assignments') }}
                    </a>
                </li>
                <li>/</li>
                <li class="font-semibold text-gray-800 dark:text-gray-100">
                    {{ isset($affectUserToGroup) ? __('Edit Assignment') : __('Create Assignment') }}
                </li>
            </ol>
        </nav>
    </div>

    {{-- Form Container --}}
    <div class="flex flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">

        {{-- Form Title --}}
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ isset($affectUserToGroup) ? __('Edit affect User to Group') : __('Create affect User to Group') }}
        </h1>
        
        <form 
            action="{{ isset($affectUserToGroup) 
                ? route('admin.groupUsers.update', $affectUserToGroup->id) 
                : route('admin.groupUsers.store') }}"
            method="POST"
            class="flex flex-col gap-4">

            @csrf
            @if(isset($affectUserToGroup))
                @method('PUT')
            @endif

            {{-- Group Selection --}}
            <x-select-typeahead 
                name="group_id" 
                label="{{ __('Assign Group') }}" 
                :options="$groupsInput->pluck('name', 'id')->toArray()" 
                :selected="old('group_id', $affectUserToGroup->group_id ?? null)" 
                placeholder="{{ __('Select a group...') }}"
                help="{{ __('Choose the group you want to assign the user to.') }}"
            />

            {{-- User Selection --}}
            <x-select-typeahead 
                name="user_id" 
                label="{{ __('Select User') }}" 
                :options="$usersInput->pluck('name', 'id')->toArray()" 
                :selected="old('user_id', $affectUserToGroup->user_id ?? null)" 
                placeholder="{{ __('Select a user...') }}"
                help="{{ __('Select the user you want to attach to the selected group.') }}"
            />

            {{-- Active Toggle --}}
            <div class="flex items-center gap-2 mt-2">
                <input type="hidden" name="is_active" value="0">
                <input 
                    type="checkbox"
                    name="is_active"
                    id="is_active"
                    value="1"
                    {{ old('is_active', $affectUserToGroup->is_active ?? true) ? 'checked' : '' }}
                    class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded 
                           focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_active" class="text-gray-700 dark:text-gray-200 font-medium">
                    {{ __('Active Assignment') }}
                </label>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end mt-4">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ isset($affectUserToGroup) ? __('Update Assignment') : __('Assign User to Group') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
