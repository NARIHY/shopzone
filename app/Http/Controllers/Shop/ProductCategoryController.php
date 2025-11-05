<?php

namespace App\Http\Controllers\Shop;

use App\Common\CategoryProductView;
use App\Events\Utils\NotificationSent;
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
    public function store(StoreProductCategoryRequest $storeProductCategoryRequest)
    {
        try {
            ProcessCreateProductCategoryJob::dispatch($storeProductCategoryRequest->validated());
            return redirect()->route('admin.product-categories.index');
        } catch (\Throwable $e) {
            return redirect()->back()->with('warning', 'There was an error during the request. Reason: ' . $e->getMessage());
        } finally {
            unset($storeProductCategoryRequest);
        }
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
    public function update(UpdateProductCategoryRequest $updateProductCategoryRequest, ProductCategory $productCategory)
    {

        try {
            $data = $updateProductCategoryRequest->validated();
            ProcessUpdateProductCategoryJob::dispatch($productCategory, $data);
            return redirect()->back();
        } catch (\Throwable $e) {
            event(new NotificationSent('warning', 'There was an error during the request. Reason: ' . $e->getMessage()));
            return redirect()->back();
        } finally {
            unset($updateProductCategoryRequest, $productCategory, $data);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        try {
            $productCategory->delete();
            event(new NotificationSent('success', __('shop.Product category deleted successfully.')));
            return redirect()->route('admin.product-categories.index');
        } catch (\Exception $e) {
            event(new NotificationSent('warning', 'There was an error during the request. Reason: ' . $e->getMessage()));
            return redirect()->back();
        } finally
        {
            unset($productCategory);
        }
    }
}
