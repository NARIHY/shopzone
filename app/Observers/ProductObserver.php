<?php

namespace App\Observers;

use App\Models\Shop\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function created(Product $product): void
    {
        $this->clearCache($product);
    }

    public function updated(Product $product): void
    {
        $this->clearCache($product);
    }

    public function deleted(Product $product): void
    {
        $this->clearCache($product);
    }

    public function restored(Product $product): void
    {
        $this->clearCache($product);
    }

    public function forceDeleted(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Invalidation centralisée
     */
    private function clearCache(Product $product): void
    {
        // Cache du produit affiché
        Cache::forget('product_show_' . $product->id);

        // Dépendances possibles
        Cache::forget('product_categories_input');
        Cache::forget('media_input');
    }
}
