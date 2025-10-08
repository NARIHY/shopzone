<div class="p-4">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            {{ __('Products List') }}
        </h1>
    </div>

    {{-- Search and Actions --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div class="relative w-full sm:w-96">
        <!-- Input with embedded button -->
        <input
            type="text"
            wire:model.defer="search"
            placeholder="{{ __('Search product...') }}"
            class="border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 w-full pr-32
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                   bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition"
        >

        <!-- Loader -->
        <div wire:loading wire:target="search" class="absolute inset-y-0 right-16 flex items-center pr-3">
            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        </div>

        <!-- Search Button inside input -->
        <button
            wire:click="applySearch"
            class="absolute inset-y-0 right-0 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-md text-sm font-medium transition"
        >
            {{ __('Search') }}
        </button>
    </div>

    <!-- New Category Button -->
    <a href="{{ route('admin.products.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap transition">
        {{ __('shop.+ New Product') }}
    </a>
</div>

    {{-- Table Card --}}
    <x-table.card 
        :columns="[
            ['label' => '#', 'align' => 'left'],
            ['label' => __('Name'), 'align' => 'center'],
            ['label' => __('Category'), 'align' => 'center'],
            ['label' => __('Price'), 'align' => 'center'],
            ['label' => __('Status'), 'align' => 'center'],
            ['label' => __('utils.Actions'), 'align' => 'center'],
        ]"
        :notice="__('shop.PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')"
    >
        {{-- Table body --}}
        @forelse($products as $product)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                    {{ $product->id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $product->name }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $product->category?->name ?? __('No category') }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                        {{ number_format($product->finalPrice(), 2) }} €
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm font-medium {{ $product->is_active ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $product->is_active ? __('Active') : __('Inactive') }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                        {{-- Edit --}}
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="inline-block px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded hover:bg-blue-50 dark:hover:bg-blue-900">
                            {{ __('utils.Edit') }}
                        </a>

                        {{-- Show --}}
                        <button wire:click="openCategoryModal({{ $product->id }})"
                                class="inline-block px-3 py-1 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">
                            {{ __('utils.Show') }}
                        </button>

                        {{-- Delete --}}
                        <button
                            onclick="if(!confirm('{{ __('Are you sure you want to delete this product?') }}')) return event.stopImmediatePropagation();"
                            wire:click="deleteProduct({{ $product->id }})"
                            class="inline-block px-3 py-1 text-sm font-medium text-red-600 border border-red-600 rounded hover:bg-red-50 dark:hover:bg-red-900 cursor-pointer">
                            {{ __('utils.Remove') }}
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        <p class="text-sm">{{ __('utils.Empty') }}</p>
                    </div>
                </td>
            </tr>
        @endforelse

        {{-- Pagination --}}
        <x-slot name="pagination">
            {{ $products->links() }}
        </x-slot>
    </x-table.card>

    {{-- Modal --}}
    @if($showModal && $selectedProduct)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4" wire:key="product-modal-{{ $selectedProduct->id }}">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg">
                {{-- Header --}}
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $selectedProduct->name }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ID</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $selectedProduct->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Category') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $selectedProduct->category?->name ?? __('N/A') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Price') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($selectedProduct->price, 2) }} €</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Stock') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $selectedProduct->stock }}</p>
                        </div>
                    </div>

                    @if($selectedProduct->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Description') }}</label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $selectedProduct->description }}</p>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.products.edit', $selectedProduct) }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                        {{ __('utils.Edit') }}
                    </a>

                    <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium">
                        {{ __('utils.Close') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
