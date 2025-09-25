<x-layouts.app :title="__('Edit or Create Product Category')">

    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit or Create Media') }}
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
                <a href="{{route('admin.media.index')}}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{__('Media files')}}
                </a></li>
            <li class="breadcrumb-item">/</li>
            <li class="breadcrumb-item font-semibold text-gray-800 dark:text-gray-100">{{__('Edit or Create Media')}}</li>
            </ol>
        </nav>
    </div>


    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">


        {{-- Form Title --}}
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ isset($category) ? __('Edit Category') : __('Create Category') }}
        </h1>

        {{-- Form --}}
        <form action="{{ isset($category) ? route('admin.media.update', $category->id) : route('admin.media.store') }}"
              method="POST"
              class="flex flex-col gap-4">

            @csrf
            @if(isset($category))
                @method('PUT')
            @endif

            {{-- Category Name --}}
            <x-form.input 
                name="title" 
                label="{{ __('Media title') }}" 
                :value="$media->name ?? ''"
            />

            <x-form.file :value="$media->paht ?? null" name="attachments" />

            {{-- Submit Button --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ isset($category) ? __('Update Media') : __('Create Media') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
