<div>
    {{-- Modal backdrop --}}
    @if($showModal)
        <div 
            class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50"
            x-data
            x-on:keydown.escape.window="$wire.close()"
        >
            {{-- Modal content --}}
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 relative z-50">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $category->name ?? __('shop.Product Category') }}
                </h2>

                <p class="text-gray-600">
                    {{ $category->description ?? 'Aucune description disponible.' }}
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <button 
                        wire:click="close" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300"
                    >
                        {{__('utils.Close')}}
                    </button>
                </div>
            </div>
        </div>
    @endif


</div>
