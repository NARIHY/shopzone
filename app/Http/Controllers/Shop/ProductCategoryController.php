<?php

namespace App\Http\Controllers\Shop;

use App\Common\CategoryProductView;
use App\Http\Controllers\Controller;
use App\Models\Shop\ProductCategory;
use App\Http\Requests\Shop\StoreProductCategoryRequest;
use App\Http\Requests\Shop\UpdateProductCategoryRequest;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view(CategoryProductView::getCategoryListView(), [
            'categories' => ProductCategory::where('is_active', true)->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(CategoryProductView::getCategoryCreateOrEditView());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        return view(CategoryProductView::getCategoryCreateOrEditView(), [
            'category' => ProductCategory::findOrFail($productCategory->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        //
    }
}
