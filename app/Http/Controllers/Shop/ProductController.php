<?php

namespace App\Http\Controllers;

use App\Models\Shop\Product;
use App\Http\Requests\Shop\StoreProductRequest;
use App\Http\Requests\Shop\UpdateProductRequest;
use App\Common\ProductView;
use App\Models\Files\Media;
use App\Models\Shop\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): View
    {
        return view(ProductView::getListView());
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view(ProductView::getCreateOrEditView(), [
            'productCategoriesInput'=> ProductCategory::all(),
            'mediaInput'=> Media::all()
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        return $this->persistProduct(new Product(), $request->validated(), 'created');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        return view(ProductView::getCreateOrEditView(), [
            'product' => Product::findOrFail($product->id),
            'productCategoriesInput'=> ProductCategory::all(),
            'mediaInput'=> Media::all()
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        return $this->persistProduct($product, $request->validated(), 'updated');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès.');
        } catch (Throwable $e) {
            return $this->redirectBackWithError($e);
        }
    }

    /* -----------------------------------------------------------------
     |  Private Helpers
     |----------------------------------------------------------------- */

    /**
     * Handle create/update logic in a single reusable method.
     */
    private function persistProduct(Product $product, array $data, string $action): RedirectResponse
    {
        try {
            $product->fill($data)->save();

            return redirect()
                ->route('admin.products.index')
                ->with('success', "Produit {$action} avec succès.");
        } catch (Throwable $e) {
            return $this->redirectBackWithError($e);
        }
    }

    /**
     * Return a redirect with an error message.
     */
    private function redirectBackWithError(Throwable $e): RedirectResponse
    {
        report($e); // good practice: logs the exception

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
    }
}
