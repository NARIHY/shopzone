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
                        {{ number_format($product->finalPrice(), 3) }} Ar
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
    <div class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50 p-4 animate-in fade-in duration-200" wire:key="product-modal-{{ $selectedProduct->id }}">
        <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-3xl overflow-hidden shadow-2xl transform transition-all animate-in zoom-in-95 duration-300">
            {{-- Header --}}
            <div class="flex justify-between items-center px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-800">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">
                    {{ $selectedProduct->name }}
                </h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors duration-200 hover:rotate-90 transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Media Carousel --}}
            <div x-data="{ current: 0 }" class="relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                @if($selectedProduct->media->count())
                    <div class="relative h-72 md:h-[28rem] flex items-center justify-center overflow-hidden">
                        @foreach($selectedProduct->media as $index => $media)
                            <div x-show="current === {{ $index }}" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="absolute inset-0 p-4">
                                @if(str_starts_with($media->mime_type, 'image'))
                                    <img src="{{ $media->url() }}" alt="{{ $media->title }}" class="object-contain w-full h-full rounded-lg">
                                @elseif(str_starts_with($media->mime_type, 'video'))
                                    <video controls class="object-contain w-full h-full rounded-lg">
                                        <source src="{{ $media->url() }}" type="{{ $media->mime_type }}">
                                        {{ __('Your browser does not support the video tag.') }}
                                    </video>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Carousel Controls --}}
                    @if($selectedProduct->media->count() > 1)
                        <div class="absolute inset-0 flex items-center justify-between px-4 pointer-events-none">
                            <button @click="current = (current === 0 ? {{ $selectedProduct->media->count() - 1 }} : current - 1)"
                                    class="pointer-events-auto bg-white/90 dark:bg-gray-800/90 text-gray-800 dark:text-gray-200 rounded-full p-3 hover:bg-white dark:hover:bg-gray-700 shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button @click="current = (current === {{ $selectedProduct->media->count() - 1 }} ? 0 : current + 1)"
                                    class="pointer-events-auto bg-white/90 dark:bg-gray-800/90 text-gray-800 dark:text-gray-200 rounded-full p-3 hover:bg-white dark:hover:bg-gray-700 shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Carousel Indicators --}}
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 bg-black/30 dark:bg-black/50 px-3 py-2 rounded-full backdrop-blur-sm">
                            @foreach($selectedProduct->media as $index => $media)
                                <button @click="current = {{ $index }}"
                                      :class="current === {{ $index }} ? 'w-8 bg-white dark:bg-gray-200' : 'w-3 bg-white/50 dark:bg-gray-400/50 hover:bg-white/70'"
                                      class="h-3 rounded-full transition-all duration-300 cursor-pointer"></button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="h-72 md:h-[28rem] flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                        <svg class="w-20 h-20 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm font-medium">{{ __('No media available') }}</p>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="p-6 space-y-5 bg-white dark:bg-gray-800">
                <div class="grid grid-cols-2 gap-5">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">ID</label>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">#{{ $selectedProduct->id }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('Category') }}</label>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $selectedProduct->category?->name ?? __('N/A') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                        <label class="block text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">{{ __('Price') }}</label>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($selectedProduct->price, 2) }} <span class="text-base">Ar</span></p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
                        <label class="block text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wider mb-2">{{ __('Stock') }}</label>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $selectedProduct->stock }} <span class="text-base">unités</span></p>
                    </div>
                </div>

                @if($selectedProduct->description)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">{{ __('Description') }}</label>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $selectedProduct->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 px-6 py-5 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <button wire:click="closeModal"
                        class="px-5 py-2.5 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 text-sm font-semibold border border-gray-300 dark:border-gray-600 transition-all duration-200 hover:shadow-md">
                    {{ __('utils.Close') }}
                </button>
                
                <a href="{{ route('admin.products.edit', $selectedProduct) }}"
                   class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ __('utils.Edit') }}
                </a>
            </div>
        </div>
    </div>
@endif

</div>
