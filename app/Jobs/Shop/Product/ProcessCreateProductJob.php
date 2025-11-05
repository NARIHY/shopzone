<?php

namespace App\Jobs\Shop\Product;

use App\Events\Utils\NotificationSent;
use App\Models\Shop\Product;
use App\Models\Files\Media;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcessCreateProductJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public array $validatedData,
        public array $decodedMediaIds = []
    ) {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $data = $this->validatedData;
            $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name']);
            $data['sku'] = $this->generateUniqueSku($data['sku'] ?? null);

            $product = Product::create($data);

            $validIds = Media::whereIn(
                'id',
                array_filter(array_map('intval', $this->decodedMediaIds))
            )->pluck('id')->toArray();

            if (!empty($validIds)) {
                $product->media()->attach($validIds);
            }
            event(new NotificationSent('success', 'Product created succeffuly.'));
        });
    }

    private function generateUniqueSlug(string $slug): string
    {
        $base = Str::slug($slug);
        $unique = $base;
        $i = 1;

        while (Product::where('slug', $unique)->exists()) {
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

        while (Product::where('sku', $unique)->exists()) {
            $unique = "{$base}-{$i}";
            $i++;
        }

        return $unique;
    }
}
