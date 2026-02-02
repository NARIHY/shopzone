<?php

namespace App\Http\Controllers\Public\Category;

use App\Http\Controllers\Controller;
use App\Models\Shop\ProductCategory;
use Illuminate\Http\Request;

class CategoryPublicController extends Controller
{
    /**
     * Summary of getProductByCategoryId
     * @param int $categoryId
     * @return \Illuminate\Contracts\View\View
     */
    public function getProductByCategoryId(int $categoryId)
    {
        $category = ProductCategory::where('id', $categoryId)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $category->products()
            ->where('is_active', true)
            ->with('media')
            ->orderBy('name')
            ->orderBy('created_at', 'desc')
            ->paginate(12); 

        return view('public.category.products.getProductsBycategory', compact(
            'category',
            'products'
        ));
    }
}
