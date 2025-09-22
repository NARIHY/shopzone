<?php

namespace App\Livewire\Shop;

use App\Models\Shop\ProductCategory;
use Livewire\Component;
use Livewire\WithPagination;
use function Livewire\Volt\protect;

class ProductCategoryList extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
         $categories = ProductCategory::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->where('name', 'like', "%{$this->search}%")
            ->paginate(10);

        return view('livewire.shop.product-category-list', [
            'categories' => $categories,
        ]);
    }
}
