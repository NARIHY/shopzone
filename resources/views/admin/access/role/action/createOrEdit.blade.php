<x-layouts.app :title="__('Edit or Create Roles')">

    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit or Create Roles') }}
        </h1>
        <nav>
            <ol class="breadcrumb flex space-x-2 text-gray-600 dark:text-gray-400">
            <li class="breadcrumb-item">
                <a href="{{route('admin.dashboard')}}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{__('Dashboard')}}
                </a>
            </li>
            <li class="breadcrumb-item">/</li>
            <li class="breadcrumb-item">
                <a href="{{route('admin.roles.index')}}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{__('Roles Managements')}}
                </a></li>
            <li class="breadcrumb-item">/</li>
            <li class="breadcrumb-item font-semibold text-gray-800 dark:text-gray-100">{{__('Edit or Create Role')}}</li>
            </ol>
        </nav>
    </div>


    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">


        {{-- Form Title --}}
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ isset($role) ? __('Edit Role') : __('Create Role') }}
        </h1>

        {{-- Form --}}
        <form action="{{ isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}"
              method="POST"
              class="flex flex-col gap-4">

            @csrf
            @if(isset($role))
                @method('PUT')
            @endif

            {{-- Role Name --}}
            <x-form.input 
                name="roleName" 
                label="{{ __('Role Name') }}" 
                :value="$role->roleName ?? ''"
            />

            {{-- Role Description --}}
            <x-form.textarea 
                name="description" 
                label="{{ __('Role Description') }}" 
                :value="$role->description ?? ''" 
                rows="5"
            />
            {{-- Is Active Toggle --}}
            <div class="flex items-center gap-2">
                <input type="checkbox"
                       name="is_active"
                       id="is_active"
                       value="1"
                       {{ old('is_active', $role->is_active ?? false) ? 'checked' : '' }}
                       class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_active" class="text-gray-700 dark:text-gray-200 font-medium">
                    {{ __('Active') }}
                </label>
            </div>


            {{-- Submit Button --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ isset($role) ? __('Update Role') : __('Create Role') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
