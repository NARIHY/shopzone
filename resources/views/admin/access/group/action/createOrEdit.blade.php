<x-layouts.app :title="__('Edit or Create Group User')">

    {{-- Page Title --}}
    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ isset($group) ? __('Edit Group User') : __('Create Group User') }}
        </h1>

        <nav>
            <ol class="breadcrumb flex space-x-2 text-gray-600 dark:text-gray-400">
                <li>
                    <a href="{{ route('admin.dashboard.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Dashboard') }}
                    </a>
                </li>
                <li>/</li>
                <li>
                    <a href="{{ route('admin.groups.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Group Users Managements') }}
                    </a>
                </li>
                <li>/</li>
                <li class="font-semibold text-gray-800 dark:text-gray-100">
                    {{ isset($group) ? __('Edit Group User') : __('Create Group User') }}
                </li>
            </ol>
        </nav>
    </div>

    {{-- Form Container --}}
    <div class="flex flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">

        <form 
            action="{{ isset($group) ? route('admin.groups.update', $group->id) : route('admin.groups.store') }}"
            method="POST"
            class="flex flex-col gap-4">

            @csrf
            @if(isset($group))
                @method('PUT')
            @endif

            {{-- Group Name --}}
            <x-form.input 
                name="name" 
                label="{{ __('Group Name') }}" 
                :value="old('name', $group->name ?? '')"
                help="Provide a unique name for the user group."
            />

            {{-- Group Description --}}
            <x-form.textarea 
                name="description" 
                label="{{ __('Group Description') }}" 
                :value="old('description', $group->description ?? '')" 
                rows="5"
                help="Provide a brief description of the user group."
            />

            {{-- Active Toggle --}}
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input 
                    type="checkbox"
                    name="is_active"
                    id="is_active"
                    value="1"
                    {{ old('is_active', $group->is_active ?? false) ? 'checked' : '' }}
                    class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded 
                           focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_active" class="text-gray-700 dark:text-gray-200 font-medium">
                    {{ __('Active') }}
                </label>
            </div>

            {{-- Assign Role --}}
            <x-select-typeahead 
                name="role_id" 
                label="{{ __('Assign Role') }}" 
                :options="$rolesInput->pluck('roleName', 'id')->toArray()" 
                :selected="old('role_id', $group->role_id ?? null)" 
                placeholder="{{ __('Select a role...') }}"
                help="Select a role to assign to this user group."
            />

            {{-- Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ isset($group) ? __('Update Group User') : __('Create Group User') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
