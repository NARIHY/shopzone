<x-layouts.app :title="__('Edit or Create affect User to Group')">

    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit or Create Group User') }}
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
                <a href="" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{__('affect User to Group')}}
                </a></li>
            <li class="breadcrumb-item">/</li>
            <li class="breadcrumb-item font-semibold text-gray-800 dark:text-gray-100">{{__('Edit or Create Group User')}}</li>
            </ol>
        </nav>
    </div>


    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">


        {{-- Form Title --}}
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ isset($affectUserToGroup) ? __('Edit affect User to Group') : __('Create affect User to Group') }}
        </h1>

        {{-- Form --}}
        <form action="{{ isset($affectUserToGroup) ? route('admin.groupUsers.update', $affectUserToGroup->id) : route('admin.groupUsers.store') }}"
              method="POST"
              class="flex flex-col gap-4">

            @csrf
            @if(isset($affectUserToGroup))
                @method('PUT')
            @endif

            {{-- groups --}}
            <div class="flex items-center gap-2">
                <x-select-typeahead 
                    name="group_id" 
                    label="{{ __('Assign Role') }}" 
                    :options="$groupsInput->pluck('name', 'id')->toArray()" 
                    :selected="old('group_id', $affectUserToGroup->group_id ?? null)" 
                    placeholder="{{ __('Select a group...') }}"
                    help="Select group to assign user to this group."
                />
            </div>

            {{-- Users groups --}}
            <div class="flex items-center gap-2">
                <x-select-typeahead 
                    name="user_id" 
                    label="{{ __('Assign Role') }}" 
                    :options="$usersInput->pluck('name', 'id')->toArray()" 
                    :selected="old('user_id', $affectUserToGroup->user_id ?? null)" 
                    placeholder="{{ __('Select a user...') }}"
                    help="Select a user to assign this user to this group chosen if above."
                />
            </div>


            {{-- Submit Button --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ isset($affectUserToGroup) ? __('Modify User to Group') : __('Affect User to Group') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
