<?php

namespace App\Livewire\Shop;

use App\Models\Shop\ProductCategory;
use Livewire\Component;

class ProductCategoryShow extends Component
{
    public $showModal= false;
    public $category;

    protected $listeners = ['showCategoryModal' => 'open'];

    public function open($categoryId)
    {
        $this->category = ProductCategory::findOrFail($categoryId);
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
    }


    public function render()
    {
        return view('livewire.shop.product-category-show');
    }
}
