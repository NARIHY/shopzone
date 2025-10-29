<x-layouts.app :title="__('Edit or Create Permission for User')">

    {{-- Page Title --}}
    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ isset($permission) ? __('Edit Permission for User') : __('Create Permission for User') }}
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
                    <a href="{{ route('admin.permissions.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Permission for User Management') }}
                    </a>
                </li>
                <li>/</li>
                <li class="font-semibold text-gray-800 dark:text-gray-100">
                    {{ isset($permission) ? __('Edit Permission for User') : __('Create Permission for User') }}
                </li>
            </ol>
        </nav>
    </div>

    {{-- Form Container --}}
    <div class="flex flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">
{{-- Form Title --}}
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ isset($permission) ? __('Edit Permission for User') : __('Create Permission for User') }}
        </h1>
        <form 
            action="{{ isset($permission) 
                ? route('admin.permissions.update', $permission->id) 
                : route('admin.permissions.store') }}"
            method="POST"
            class="flex flex-col gap-4">

            @csrf
            @if(isset($permission))
                @method('PUT')
            @endif

            {{-- Permission Name --}}
            <x-form.input 
                name="name" 
                label="{{ __('Permission Name') }}" 
                :value="old('name', $permission->name ?? '')"
                help="{{ __('Provide a unique name for the permission.') }}"
            />

            {{-- Permission Description --}}
            <x-form.textarea 
                name="description" 
                label="{{ __('Permission Description') }}" 
                :value="old('description', $permission->description ?? '')" 
                rows="5"
                help="{{ __('Provide a brief description of what this permission allows.') }}"
            />

            {{-- Is Active Toggle --}}
            <div class="flex items-center gap-2 mt-2">
                <input type="hidden" name="is_active" value="0">
                <input 
                    type="checkbox"
                    name="is_active"
                    id="is_active"
                    value="1"
                    {{ old('is_active', $permission->is_active ?? false) ? 'checked' : '' }}
                    class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded 
                           focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_active" class="text-gray-700 dark:text-gray-200 font-medium">
                    {{ __('Active') }}
                </label>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end mt-4">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ isset($permission) ? __('Update Permission for User') : __('Create Permission for User') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
