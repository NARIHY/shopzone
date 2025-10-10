<x-layouts.app :title="__('Edit or Create Product')">

    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit or Create Product') }}
        </h1>
        <nav>
            <ol class="breadcrumb flex space-x-2 text-gray-600 dark:text-gray-400">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="breadcrumb-item">/</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('shop.Product') }}
                    </a>
                </li>
                <li class="breadcrumb-item">/</li>
                <li class="breadcrumb-item font-semibold text-gray-800 dark:text-gray-100">
                    {{ __('Edit or Create Product') }}
                </li>
            </ol>
        </nav>
    </div>

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">

        {{-- Form Title --}}
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ isset($product) ? __('Edit Product') : __('Create Product') }}
        </h1>

        {{-- Form --}}
        <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
              method="POST"
              class="flex flex-col gap-4">

            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            {{-- Product Name --}}
            @if(isset($product))
                <x-form.input 
                    name="name" 
                    label="{{ __('Product Name') }}" 
                    :value="$product->name ?? ''"
                    help="Enter the name of the product."
                />
            @else 
             <x-form.input 
                name="name" 
                label="{{ __('Product Name') }}" 
                :value="old('name') ?? ''"
                help="Enter the name of the product."
            />
            @endif

            {{-- Product Slug --}}
            @if(isset($product))
                <x-form.input 
                name="slug" 
                label="{{ __('Product Slug') }}" 
                :value="$product->slug ?? ''"
                help="Enter the slug of the product."
            />
            @else 
            <x-form.input 
                name="slug" 
                label="{{ __('Product Slug') }}" 
                :value="old('slug') ?? ''"
                help="Enter the slug of the product."
            />
            @endif

            {{-- Product Description --}}
            @if (isset($product))
            <x-form.textarea 
                name="description" 
                label="{{ __('Description') }}" 
                :value="$product->description ?? ''" 
                rows="5"
                help="Provide a detailed description of the product."
            />
            @else 
            <x-form.textarea 
                name="description" 
                label="{{ __('Description') }}" 
                :value="old('description') ?? ''" 
                rows="5"
                help="Provide a detailed description of the product."
            />
            @endif

            {{-- Product Price --}}
            @if(isset($product))
<x-input.price-input  
                name="price"
                label="{{ __('Product Price') }}"
                value="{{ $product->price ?? 0 }}"
                help="{{ __('Enter the price of the product in Ariary.') }}"
            />
            @else
            <x-input.price-input  
                name="price"
                label="{{ __('Product Price') }}"
                value="{{ old('price') ?? 0 }}"
                help="{{ __('Enter the price of the product in Ariary.') }}"
            /> 
            @endif

            {{-- Discount Price --}}
            @if (isset($product))
            <x-input.price-input 
                name="discount_price"
                label="{{ __('Discount Product Price') }}"
                value="{{ $product->discount_price ?? 0 }}"
                help="{{ __('Enter the discount price of the product in Ariary.') }}"
            />
            @else
            <x-input.price-input 
                name="discount_price"
                label="{{ __('Discount Product Price') }}"
                value="{{ old('discount_price') ?? 0 }}"
                help="{{ __('Enter the discount price of the product in Ariary.') }}"
            />
            @endif

            {{-- Stock --}}
            @if(isset($product))
            <x-input.number 
                name="stock"
                label="{{ __('Quantity in stock') }}"
                value="{{ $product->stock ?? 0 }}"
                min="0"
                max="1000"
                step="1"
                suffix="units"
                help="{{ __('Indicate the number of items available.') }}"
            />
            @else 
            <x-input.number 
                            name="stock"
                            label="{{ __('Quantity in stock') }}"
                            value="{{ old('stock') ?? 0 }}"
                            min="0"
                            max="1000"
                            step="1"
                            suffix="units"
                            help="{{ __('Indicate the number of items available.') }}"
                        />
            @endif

            
            {{-- Media Relations --}}
            <div class="flex items-center gap-2">
                <x-select-typeahead-multiple 
                    name="media[]"                      {{-- <-- toujours media[] pour multiple --}}
                    :options="$mediaInput->pluck('title', 'id')->toArray()"
                    :selected="old('media', isset($product) ? $product->media->pluck('id')->toArray() : [])"
                    placeholder="{{ __('Assign Media') }}"
                    help="{{ __('Select one or many media files associated with this product.') }}"
                />
            </div>

            {{-- Product SKU --}}
            <x-form.input 
                name="sku" 
                label="{{ __('Product Unique Identifier (SKU)') }}" 
                :value="$product->sku ?? ''"
                help="{{ __('Enter a unique code for the product, e.g., TSH-RED-M.') }}"
            />

            {{-- Product category --}}
<div class="flex items-center gap-2">
                @if(isset($product))
                <x-select-typeahead 
                    name="product_category_id" 
                    label="{{ __('Assign Product category') }}" 
                    :options="$productCategoriesInput->pluck('name', 'id')->toArray()" 
                    :selected="old('product_category_id', $product->category()->id ?? null)" 
                    placeholder="{{ __('Select a role...') }}"
                    help="Select a category to assign to this product."
                />
                @else 
                <x-select-typeahead 
                    name="product_category_id" 
                    label="{{ __('Assign Product category') }}" 
                    :options="$productCategoriesInput->pluck('name', 'id')->toArray()" 
                    :selected="old('product_category_id', old('product_category_id') ?? null)" 
                    placeholder="{{ __('Select a category...') }}"
                    help="Select a category to assign to this product."
                />
                @endif
            </div>


            {{-- Is Active Toggle --}}
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox"
                       name="is_active"
                       id="is_active"
                       value="1"
                       {{ old('is_active', $product->is_active ?? false) ? 'checked' : '' }}
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
                    {{ isset($product) ? __('Update Product') : __('Create Product') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
