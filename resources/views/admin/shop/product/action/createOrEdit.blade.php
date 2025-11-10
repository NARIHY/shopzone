<x-layouts.app :title="__('Edit or Create Product')">

    {{-- Page Title --}}
    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ isset($product) ? __('Edit Product') : __('Create Product') }}
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
                    <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('shop.Product') }}
                    </a>
                </li>
                <li>/</li>
                <li class="font-semibold text-gray-800 dark:text-gray-100">
                    {{ isset($product) ? __('Edit Product') : __('Create Product') }}
                </li>
            </ol>
        </nav>
    </div>

    {{-- Form Container --}}
    <div class="flex flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">

        <form 
            action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
            method="POST"
            class="flex flex-col gap-4">

            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            {{-- Product Name --}}
            <x-form.input 
                name="name" 
                label="{{ __('Product Name') }}" 
                :value="old('name', $product->name ?? '')"
                help="Enter the name of the product."
            />

            {{-- Product Slug --}}
            <x-form.input 
                name="slug" 
                label="{{ __('Product Slug') }}" 
                :value="old('slug', $product->slug ?? '')"
                help="Enter the slug of the product."
            />

            {{-- Product Description --}}
            <x-form.textarea 
                name="description" 
                label="{{ __('Description') }}" 
                :value="old('description', $product->description ?? '')" 
                rows="5"
                help="Provide a detailed description of the product."
            />

            {{-- Product Price --}}
            <x-input.price-input  
                name="price"
                label="{{ __('Product Price') }}"
                value="{{ old('price', $product->price ?? 0) }}"
                help="{{ __('Enter the price of the product in Ariary.') }}"
            />

            {{-- Discount Price --}}
            <x-input.price-input 
                name="discount_price"
                label="{{ __('Discount Product Price') }}"
                value="{{ old('discount_price', $product->discount_price ?? 0) }}"
                help="{{ __('Enter the discount price of the product in Ariary.') }}"
            />

            {{-- Stock --}}
            <x-input.number 
                name="stock"
                label="{{ __('Quantity in stock') }}"
                value="{{ old('stock', $product->stock ?? 0) }}"
                min="0"
                max="1000"
                step="1"
                suffix="units"
                help="{{ __('Indicate the number of items available.') }}"
            />

            {{-- Media Relations --}}
            <x-select-typeahead-multiple 
                name="media[]"
                :options="$mediaInput->pluck('title', 'id')->toArray()"
                :selected="old('media', isset($product) ? $product->media->pluck('id')->toArray() : [])"
                placeholder="{{ __('Assign Media') }}"
                help="{{ __('Select one or many media files associated with this product.') }}"
            />

            {{-- SKU --}}
            <x-form.input 
                name="sku" 
                label="{{ __('Product Unique Identifier (SKU)') }}" 
                :value="old('sku', $product->sku ?? '')"
                help="{{ __('Enter a unique code for the product, e.g., TSH-RED-M.') }}"
            />

            {{-- Product Category --}}
            <x-select-typeahead 
                name="product_category_id" 
                label="{{ __('Assign Product Category') }}" 
                :options="$productCategoriesInput->pluck('name', 'id')->toArray()" 
                :selected="old('product_category_id', $product->product_category_id ?? '')" 
                placeholder="{{ __('Select a category...') }}"
                help="Select a category to assign to this product."
            />

            {{-- Active Toggle --}}
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input 
                    type="checkbox"
                    name="is_active"
                    id="is_active"
                    value="1"
                    {{ old('is_active', $product->is_active ?? false) ? 'checked' : '' }}
                    class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded 
                           focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_active" class="text-gray-700 dark:text-gray-200 font-medium">
                    {{ __('Active') }}
                </label>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ isset($product) ? __('Update Product') : __('Create Product') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
