<x-layouts.app :title="__('Edit or Create Media')">

    {{-- Page Title --}}
    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ isset($media) ? __('Edit Media') : __('Create Media') }}
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
                    <a href="{{ route('admin.media.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Media Files') }}
                    </a>
                </li>
                <li>/</li>
                <li class="font-semibold text-gray-800 dark:text-gray-100">
                    {{ isset($media) ? __('Edit Media') : __('Create Media') }}
                </li>
            </ol>
        </nav>
    </div>

    {{-- Form Container --}}
    <div class="flex flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">

        <form 
            action="{{ isset($media) ? route('admin.media.update', $media->id) : route('admin.media.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="flex flex-col gap-4">

            @csrf
            @if(isset($media))
                @method('PUT')
            @endif

            {{-- Media Title --}}
            <x-form.input 
                name="title" 
                label="{{ __('Media Title') }}" 
                :value="old('title', $media->title ?? '')"
                help="Enter a descriptive title for the media file."
            />

            {{-- File Upload --}}
            <x-form.file 
                name="file"
                :value="$media->path ?? null"
                help="{{ __('Drag and drop the file here (PDF, PNG, JPG, MP4, etc.)') }}"
            />

            {{-- Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ isset($media) ? __('Update Media') : __('Create Media') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
