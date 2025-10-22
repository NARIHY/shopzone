<?php

namespace App\Http\Controllers\Shop;

use App\Common\CategoryProductView;
use App\Http\Controllers\Controller;
use App\Models\Shop\ProductCategory;
use App\Http\Requests\Shop\StoreProductCategoryRequest;
use App\Http\Requests\Shop\UpdateProductCategoryRequest;
use App\Jobs\Shop\Category\ProcessCreateProductCategoryJob;
use App\Jobs\Shop\Category\ProcessUpdateProductCategoryJob;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view(CategoryProductView::getListView());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(CategoryProductView::getCreateOrEditView());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {
        ProcessCreateProductCategoryJob::dispatch($request->validated());
        return redirect()->route('admin.product-categories.index')->with('success', __('Your product category is now queued for creation. You will be notified once it is done.'));
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
        return view(CategoryProductView::getCreateOrEditView(), [
            'category' => ProductCategory::findOrFail($productCategory->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $data = $request->validated();
        ProcessUpdateProductCategoryJob::dispatch($productCategory, $data);

        return redirect()->back()
                        ->with('success', __('Your product category is now queued for update.  You will be notified once it is done.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        try {
            $productCategory->delete();
            return redirect()->route('admin.product-categories.index')
                            ->with('success', __('shop.Product category deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
