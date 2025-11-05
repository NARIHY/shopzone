<?php

namespace App\Jobs\Shop\Product;

use App\Events\Utils\NotificationSent;
use App\Models\Shop\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ProcessUpdateProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uniqueFor = 3600;

    public Product $product;
    public array $validatedData;
    public array $mediaIds;

    public function __construct(Product $product, array $validatedData, array $mediaIds = [])
    {
        $this->product = $product;
        $this->validatedData = $validatedData;
        $this->mediaIds = $mediaIds;

        $this->onQueue('default');
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $data = $this->validatedData;

            $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name']);
            $data['sku'] = $this->generateUniqueSku($data['sku'] ?? null);

            $this->product->update($data);

            if (!empty($this->mediaIds)) {
                $this->product->media()->sync($this->mediaIds);
            }

            event(new NotificationSent('success', 'Product updated succeffuly.'));
        });
    }

    private function generateUniqueSlug(string $slug): string
    {
        $base = Str::slug($slug);
        $unique = $base;
        $i = 1;

        while (
            Product::where('slug', $unique)
                ->where('id', '!=', $this->product->id)
                ->exists()
        ) {
            $unique = "{$base}-{$i}";
            $i++;
        }

        return $unique;
    }

    private function generateUniqueSku(?string $sku): string
    {
        $base = $sku ?: 'SKU-' . strtoupper(Str::random(6));
        $unique = $base;
        $i = 1;

        while (
            Product::where('sku', $unique)
                ->where('id', '!=', $this->product->id)
                ->exists()
        ) {
            $unique = "{$base}-{$i}";
            $i++;
        }

        return $unique;
    }
}
