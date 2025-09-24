<x-table.card 
    :columns="[
        ['label' => '#', 'align' => 'left'],
        ['label' => __('shop.Name Catgory'), 'align' => 'left'],
        ['label' => __('utils.Actions'), 'align' => 'center'],
    ]"
    :notice="__('shop.PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')"
>
    {{-- Corps du tableau --}}
    @forelse($categories as $category)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                {{ $category->id }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $category->name }}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                    {{-- Boutons --}}
                    <a href="{{ route('admin.product-categories.edit', $category) }}"
                       class="inline-block px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded hover:bg-blue-50 dark:hover:bg-blue-900">
                        {{ __('utils.Edit') }}
                    </a>

                    <button wire:click="openCategoryModal({{ $category->id }})"
                            class="inline-block px-3 py-1 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700">
                        {{ __('utils.Show') }}
                    </button>

                    <button
                        onclick="if(!confirm('{{ __('Êtes-vous sûr de supprimer cette catégorie ?') }}')) return event.stopImmediatePropagation();"
                        wire:click="deleteCategory({{ $category->id }})"
                        class="inline-block px-3 py-1 text-sm font-medium text-red-600 border border-red-600 rounded hover:bg-red-50 dark:hover:bg-red-900">
                        {{ __('utils.Remove') }}
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="px-6 py-8 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <p class="text-sm">{{ __('utils.Empty') }}</p>
                </div>
            </td>
        </tr>
    @endforelse

    {{-- Slot pagination --}}
    <x-slot name="pagination">
        {{ $categories->links() }}
    </x-slot>
</x-table.card>
