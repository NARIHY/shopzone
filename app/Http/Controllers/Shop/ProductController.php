<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop\Product;
use App\Http\Requests\Shop\StoreProductRequest;
use App\Http\Requests\Shop\UpdateProductRequest;
use App\Common\ProductView;
use App\Http\Controllers\Controller;
use App\Models\Files\Media;
use App\Models\Shop\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;
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
            'productCategoriesInput' => ProductCategory::all(),
            'mediaInput' => Media::all()
        ]);
    }

    /**
     * Here’s the logic:
     * 1. Validate the incoming data (already done by StoreProductRequest).
     * 2. Create the product with the validated data.
     * 3. Associate the selected media to the product (one or more, but re-check if each media exists).
     * 4. If the slug is already taken, create a new slug and append suffixes to make it unique.
     * 5. The product price must be greater than the discount price.
     * 6. Quantity in stock must be greater than or equal to 0.
     * 7. SKU must be unique if provided. If not, generate a unique SKU based on the provided one.
     * 
     * Important: Loop through this logic in a try-catch block to handle errors.
     */

    public function store(StoreProductRequest $request): RedirectResponse
    {
        dd($request->validated());
        return $this->persistProduct(new Product(), $request->validated(), 'created');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        return view(ProductView::getCreateOrEditView(), [
            'product' => Product::findOrFail($product->id),
            'productCategoriesInput' => ProductCategory::all(),
            'mediaInput' => Media::all()
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
    protected function persistProduct(Product $product, array $data, string $action): RedirectResponse
    {
        try {
            // Pré-validation côté contrôleur (défensive)
            if (isset($data['discount_price']) && $data['discount_price'] >= $data['price']) {
                return redirect()->back()->withInput()->with('error', 'The discount price must be lower than the regular price.');
            }
            if (isset($data['stock']) && $data['stock'] < 0) {
                return redirect()->back()->withInput()->with('error', 'Stock must be zero or greater.');
            }

            // Normaliser la clé media pour la suite
            $mediaIds = $data['media[]'] ?? [];

            dd($mediaIds);

            DB::transaction(function () use ($product, $data, $mediaIds) {
                // Génération slug/sku avant save
                $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? ($data['name'] ?? ''), $product->id ?? null);
                $data['sku']  = $this->generateUniqueSku($data['sku'] ?? null, $product->id ?? null);

                // Fill & save
                $product->fill($data);
                $product->save();

                // Association médias (si fournis)
                if (!empty($mediaIds) && is_array($mediaIds)) {
                    $product->media()->sync($mediaIds);
                }
            });

            dd($product);
            // redirection cohérente (utilise le namespace de tes routes)
            return redirect()->route('admin.products.index')
                ->with('success', "Product successfully {$action}.");

        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }


    /* ------------------------------
 | Helpers pour slug et SKU
 |------------------------------- */
    private function generateUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($slug);
        $newSlug = $baseSlug;
        $i = 1;

        while (Product::where('slug', $newSlug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $newSlug = "{$baseSlug}-{$i}";
            $i++;
        }

        return $newSlug;
    }

    private function generateUniqueSku(?string $sku, ?int $ignoreId = null): string
    {
        if (!$sku) {
            $sku = 'SKU-' . strtoupper(Str::random(6));
        }

        $baseSku = $sku;
        $newSku = $baseSku;
        $i = 1;

        while (Product::where('sku', $newSku)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $newSku = "{$baseSku}-{$i}";
            $i++;
        }

        return $newSku;
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
