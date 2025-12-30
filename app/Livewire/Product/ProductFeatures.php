<?php

namespace App\Livewire\Product;

use App\Models\Shop\Product;
use Livewire\Component;

class ProductFeatures extends Component
{
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }
    public function render()
    {
        return view('livewire.product.product-features');
    }
}
