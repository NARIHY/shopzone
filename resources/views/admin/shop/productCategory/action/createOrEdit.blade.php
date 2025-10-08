<x-layouts.app :title="__('Edit or Create Product Category')">

    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit or Create Product Category') }}
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
                <a href="{{route('admin.product-categories.index')}}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{__('shop.Product Category')}}
                </a></li>
            <li class="breadcrumb-item">/</li>
            <li class="breadcrumb-item font-semibold text-gray-800 dark:text-gray-100">{{__('Edit or Create Product Category')}}</li>
            </ol>
        </nav>
    </div>


    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">


        {{-- Form Title --}}
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ isset($category) ? __('Edit Category') : __('Create Category') }}
        </h1>

        {{-- Form --}}
        <form action="{{ isset($category) ? route('admin.product-categories.update', $category->id) : route('admin.product-categories.store') }}"
              method="POST"
              class="flex flex-col gap-4">

            @csrf
            @if(isset($category))
                @method('PUT')
            @endif

            {{-- Category Name --}}
            <x-form.input 
                name="name" 
                label="{{ __('Category Name') }}" 
                :value="$category->name ?? ''"
                help="Enter the name of the product category."
            />

            {{-- Category Description --}}
            <x-form.textarea 
                name="description" 
                label="{{ __('Description') }}" 
                :value="$category->description ?? ''" 
                rows="5"
                help="Provide a brief description of the product category."
            />




            {{-- Is Active Toggle --}}
            <div class="flex items-center gap-2">
                <input type="checkbox"
                       name="is_active"
                       id="is_active"
                       value="1"
                       {{ old('is_active', $category->is_active ?? false) ? 'checked' : '' }}
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
                    {{ isset($category) ? __('Update Category') : __('Create Category') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
