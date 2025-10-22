<?php

namespace App\Jobs\Shop\Category;

use App\Models\Shop\ProductCategory;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessCreateProductCategoryJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    public ProductCategory $category;
    public $uniqueFor = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
        $this->onQueue('default');
    }

     /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->category->id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Normaliser ou transformer les données si besoin
            $payload = [
                'name'        => $this->data['name'],
                'description' => $this->data['description'] ?? null,
                'is_active'   => $this->data['is_active'],
            ];

            // Créer la catégorie
            $category = ProductCategory::create($payload);

            // Exemple : tu peux aussi lancer d'autres actions ici
            // event(new ProductCategoryCreated($category));
            // dispatch(new IndexCategoryInSearchEngine($category));

            session()->flash('success', __('Product category create queued successfully.'));

            Log::info('Product category created successfully via job', [
                'category_id' => $category->id,
                'name' => $category->name,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create product category via job', [
                'error' => $e->getMessage(),
            ]);

            // Si tu veux relancer le job automatiquement en cas d’échec
            $this->fail($e);
        }
    }
}
