<x-layouts.app :title="__('Create or Edit Product Category')">
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
            <div class="flex flex-col gap-1">
                <label for="name" class="text-gray-700 dark:text-gray-200 font-medium">{{ __('Category Name') }}</label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $category->name ?? '') }}"
                       class="border rounded px-3 py-2 w-full
                              bg-gray-50 dark:bg-gray-700
                              text-gray-900 dark:text-gray-100
                              border-gray-300 dark:border-gray-600
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Category Description --}}
            <div class="flex flex-col gap-1">
                <label for="description" class="text-gray-700 dark:text-gray-200 font-medium">{{ __('Description') }}</label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="border rounded px-3 py-2 w-full
                                 bg-gray-50 dark:bg-gray-700
                                 text-gray-900 dark:text-gray-100
                                 border-gray-300 dark:border-gray-600
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $category->description ?? '') }}</textarea>

                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

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
