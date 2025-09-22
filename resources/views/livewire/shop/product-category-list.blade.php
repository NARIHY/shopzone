<div class="p-4">
    <div class="flex justify-between mb-4">
        <input type="text" wire:model="search" placeholder="{{ __('Search categories...') }}"
               class="border rounded px-2 py-1 w-1/3">
        <a href="{{ route('product-categories.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded">
            {{__('+ New Category')}}
        </a>
    </div>

    <table class="w-full table-auto border-collapse border">
        <caption class="caption-top">
            {{__('PROFESSIONAL PLAN REQUIRED TO USE THIS FEATURE')}}
        </caption>
        <thead>
            <tr >
                <th class="border px-3 py-2">#</th>
                <th class="border px-3 py-2">
                    {{ __('Name') }}
                </th>
                <th class="border px-3 py-2">
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td class="border px-3 py-2">{{ $category->id }}</td>
                    <td class="border px-3 py-2">{{ $category->name }}</td>
                    <td class="border px-3 py-2 text-center">
                        <a href="{{ route('product-categories.edit', $category) }}" class="text-blue-600 text-center">
                            {{__('Edit')}}
                        </a>
                        <button wire:click="$emit('deleteCategory', {{ $category->id }})" 
                                class="text-red-600 text-center ml-2">
                            {{__('Remove')}}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
