<?php

namespace App\Http\Controllers\Shop;

use App\Common\ProductView;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreProductRequest;
use App\Http\Requests\Shop\UpdateProductRequest;
use App\Models\Files\Media;
use App\Models\Shop\Product;
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
            'mediaInput' => Media::all(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $validatedData['price'] = $this->normalizePrice($request->input('price_raw', $validatedData['price'] ?? 0));
        $validatedData['discount_price'] = $this->normalizePrice($request->input('discount_price_raw', $validatedData['discount_price'] ?? null));

        if ($this->isPriceTooHigh($validatedData['price'])) {
            return back()->withInput()->with('error', 'The price must not exceed 99,999,999.99.');
        }

        if ($this->isPriceTooHigh($validatedData['discount_price'] ?? null)) {
            return back()->withInput()->with('error', 'The discount price must not exceed 99,999,999.99.');
        }

        if ($this->isInvalidDiscount($validatedData)) {
            return back()->withInput()->with('error', 'The discount price must be lower than the regular price.');
        }

        if ($this->isInvalidStock($validatedData['stock'] ?? null)) {
            return back()->withInput()->with('error', 'Stock must be zero or greater.');
        }

        $mediaInput = $validatedData['media'] ?? [];
        $decodedMediaIds = json_decode($mediaInput[0] ?? '[]', true);

        try {
            DB::transaction(function () use ($validatedData, $decodedMediaIds) {
                $validatedData['slug'] = $this->generateUniqueSlug($validatedData['slug'] ?? $validatedData['name']);
                $validatedData['sku'] = $this->generateUniqueSku($validatedData['sku'] ?? null);

                $newProduct = Product::create($validatedData);

                foreach ($decodedMediaIds as $mediaId) {
                    $newProduct->media()->attach($mediaId);
                }
            });

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product successfully created.');
        } catch (Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'An error occurred: ' . $exception->getMessage());
        }
    }

    public function edit(Product $product): View
    {
        return view(ProductView::getCreateOrEditView(), [
            'product' => $product,
            'productCategoriesInput' => ProductCategory::all(),
            'mediaInput' => Media::all(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validatedData = $request->validated();

        if ($this->isInvalidDiscount($validatedData)) {
            return back()->withInput()->with('error', 'The discount price must be lower than the regular price.');
        }

        if ($this->isInvalidStock($validatedData['stock'] ?? null)) {
            return back()->withInput()->with('error', 'Stock must be zero or greater.');
        }

        $mediaInput = $validatedData['media'] ?? [];
        $mediaIds = array_filter(array_map('intval', json_decode($mediaInput[0] ?? '[]', true)));

        try {
            DB::transaction(function () use ($product, $validatedData, $mediaIds) {
                $validatedData['slug'] = $this->generateUniqueSlug($validatedData['slug'] ?? $validatedData['name'], $product->id);
                $validatedData['sku'] = $this->generateUniqueSku($validatedData['sku'] ?? null, $product->id);

                $product->update($validatedData);

                if (!empty($mediaIds) && is_array($mediaIds)) {
                    $product->media()->sync($mediaIds);
                }
            });

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product successfully updated.');
        } catch (Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'An error occurred: ' . $exception->getMessage());
        }
    }

    public function destroy(Product $product): RedirectResponse
    {
        try {
            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès.');
        } catch (Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Une erreur est survenue : ' . $exception->getMessage());
        }
    }

    // ---------- PRIVATE HELPERS ---------- //

    private function generateUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($slug);
        $uniqueSlug = $baseSlug;
        $suffix = 1;

        while (
            Product::where('slug', $uniqueSlug)
                ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $uniqueSlug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $uniqueSlug;
    }

    private function generateUniqueSku(?string $sku, ?int $ignoreId = null): string
    {
        $baseSku = $sku ?: 'SKU-' . strtoupper(Str::random(6));
        $uniqueSku = $baseSku;
        $suffix = 1;

        while (
            Product::where('sku', $uniqueSku)
                ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $uniqueSku = "{$baseSku}-{$suffix}";
            $suffix++;
        }

        return $uniqueSku;
    }

    private function normalizePrice($rawPrice): ?float
    {
        if ($rawPrice === null || $rawPrice === '') {
            return null;
        }

        $normalizedValue = str_replace(',', '.', $rawPrice);
        $normalizedValue = preg_replace('/[^\d.]/u', '', $normalizedValue);

        return (float) $normalizedValue;
    }

    private function isPriceTooHigh(?float $price): bool
    {
        return !is_null($price) && $price > 99999999.99;
    }

    private function isInvalidDiscount(array $data): bool
    {
        return isset($data['discount_price'], $data['price'])
            && $data['discount_price'] >= $data['price'];
    }

    private function isInvalidStock(?int $stock): bool
    {
        return !is_null($stock) && $stock < 0;
    }
}
