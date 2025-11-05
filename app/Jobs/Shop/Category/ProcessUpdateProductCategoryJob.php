<?php

namespace App\Jobs\Shop\Category;

use App\Events\Utils\NotificationSent;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessUpdateProductCategoryJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uniqueFor = 3600;
    public function __construct(
        public ProductCategory $productCategory,
        public array $data
    ) {}

    public function uniqueId(): string
    {
        return $this->productCategory->id;
    }
    public function handle(): void
    {
        try {
            $productCategory = ProductCategory::findOrFail($this->productCategory->id);

            $productCategory->update($this->data);
            event(new NotificationSent('success', 'Product category updated queued successfully.'));
            
            
        } catch (\Exception $e) {
            // Gestion complète de l'erreur
        event(new NotificationSent('warning', "Error updating ProductCategory [{$this->productCategory->id}]: " . $e->getMessage()));
            
            // Relancer l'exception si tu veux que la queue retente
            throw $e;
        }
    }
}
