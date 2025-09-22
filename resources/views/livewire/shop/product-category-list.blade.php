<div class="p-4">
    <div class="flex justify-between mb-4">
        <input type="text" wire:model="search" placeholder="{{ __('shop.Search categories...') }}"
            class="border rounded px-2 py-1 w-1/3">
        <a href="{{ route('product-categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{__('shop.+ New Category')}}
        </a>
    </div>

    <table class="w-full table-auto border-collapse border">
        <caption class="caption-top">
            {{__('shop.PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')}}
        </caption>
        <thead>
            <tr>
                <th class="border px-3 py-2">#</th>
                <th class="border px-3 py-2">
                    {{ __('shop.Name Catgory') }}
                </th>
                <th class="border px-3 py-2">
                    {{ __('utils.Actions') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td class="border px-3 py-2">{{ $category->id }}</td>
                    <td class="border px-3 py-2">{{ $category->name }}</td>
                    <td class="border px-3 py-2 text-center space-x-2">
                        {{-- Edit --}}
                        <a href="{{ route('product-categories.edit', $category) }}"
                            class="inline-block px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition">
                            {{ __('utils.Edit') }}
                        </a>
                        
                        {{-- Show --}}
                        <button wire:click="$dispatch('showCategoryModal', {{ $category->id }})"
                            class="inline-block px-3 py-1 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            {{ __('utils.Show') }}
                        </button>


                        {{-- Remove --}}
                        <button wire:click="$dispatch('deleteCategory', {{ $category->id }})"
                            class="inline-block px-3 py-1 text-sm font-medium text-red-600 border border-red-600 rounded-lg hover:bg-red-50 transition">
                            {{ __('utils.Remove') }}
                        </button>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    <!-- REND UNE FOIS le modal Livewire (hors boucle) -->
    <livewire:shop.product-category-show />
</div>