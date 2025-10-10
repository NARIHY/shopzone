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
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    public function index(): View
    {
        return view(ProductView::getListView());
    }

    public function create(): View
    {
        return view(ProductView::getCreateOrEditView(), [
            'productCategoriesInput' => ProductCategory::all(),
            'mediaInput' => Media::all()
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
{
    $data = $request->validated();

    // On prend les valeurs "raw" si elles existent, sinon on nettoie les autres
    $data['price'] = $this->normalizePrice($request->input('price_raw', $data['price'] ?? 0));
    $data['discount_price'] = $this->normalizePrice($request->input('discount_price_raw', $data['discount_price'] ?? null));

    try {
        if (isset($data['discount_price']) && $data['discount_price'] >= $data['price']) {
            return back()->withInput()->with('error', 'The discount price must be lower than the regular price.');
        }

        if (isset($data['stock']) && $data['stock'] < 0) {
            return back()->withInput()->with('error', 'Stock must be zero or greater.');
        }

        $mediaIds = $data['media'] ?? [];

        if (is_array($mediaIds)) {
        $mediaIds = array_map(function ($item) {
            // Si c'est JSON, decode
            if (is_string($item) && str_starts_with($item, '[')) {
                return json_decode($item, true);
            }
            return $item;
        }, $mediaIds);

        // Aplatir si besoin
        $mediaIds = array_merge(...$mediaIds);
    }

        DB::transaction(function () use ($data, $mediaIds) {
            $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name']);
            $data['sku'] = $this->generateUniqueSku($data['sku'] ?? null);

            $product = Product::create($data);

            if (!empty($mediaIds) && is_array($mediaIds)) {
                $product->media()->sync($mediaIds);
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product successfully created.');

    } catch (Throwable $e) {
        report($e);
        return back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}


    public function edit(Product $product): View
    {
        return view(ProductView::getCreateOrEditView(), [
            'product' => $product,
            'productCategoriesInput' => ProductCategory::all(),
            'mediaInput' => Media::all()
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        try {
            if (isset($data['discount_price']) && $data['discount_price'] >= $data['price']) {
                return back()->withInput()->with('error', 'The discount price must be lower than the regular price.');
            }

            if (isset($data['stock']) && $data['stock'] < 0) {
                return back()->withInput()->with('error', 'Stock must be zero or greater.');
            }

            $mediaIds = $data['media'] ?? [];

            DB::transaction(function () use ($product, $data, $mediaIds) {
                $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name'], $product->id);
                $data['sku'] = $this->generateUniqueSku($data['sku'] ?? null, $product->id);

                $product->update($data);

                if (!empty($mediaIds) && is_array($mediaIds)) {
                    $product->media()->sync($mediaIds);
                }
            });

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product successfully updated.');

        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product): RedirectResponse
    {
        try {
            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    private function generateUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($slug);
        $newSlug = $baseSlug;
        $i = 1;

        while (Product::where('slug', $newSlug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
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

        while (Product::where('sku', $newSku)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $newSku = "{$baseSku}-{$i}";
            $i++;
        }

        return $newSku;
    }

    private function normalizePrice($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Supprime espaces classiques et espaces fines (U+202F), remplace virgules par points
        $clean = str_replace([','], ['.'], $value);
        $clean = preg_replace('/[^\d.]/u', '', $clean);

        return (float) $clean;
    }


}
