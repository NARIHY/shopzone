<?php

namespace App\Livewire\Shop;

use App\Models\Shop\ProductCategory;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Livewire\WithPagination;

class ProductCategoryList extends Component
{
    use WithPagination;

    // UI / state
    public string $search = '';
 
    protected $queryString = [
        'search' => ['except' => ''], // ne pas inclure dans l'URL si vide
    ];

    public bool $showModal = false;
    public ?ProductCategory $selectedCategory = null;


    // listeners — ne pas typer (conflit avec Livewire\Component)
    protected $listeners = [
        'showCategoryModal' => 'openCategoryModal',
        'echo:product-category-changed,ProductCategoryChanged' => 'refreshTable',
    ];

    public function refreshTable(): void
    {
        // Vider le cache
        Cache::forget('product_categories_' . md5($this->search));

        // Rafraîchir Livewire
        $this->resetPage();
        $this->dispatch('$refresh');
    }

    /**
     * Quand on tape dans la recherche, on revient à la 1ère page.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function applySearch(): void
    {
        $this->resetPage();
    }

    /**
     * Ouvre la modal de détail avec l'ID de catégorie.
     * On caste l'id en int pour être tolérant si Livewire envoie une string.
     */
    public function openCategoryModal($id): void
    {
        $id = (int) $id;
        $this->selectedCategory = ProductCategory::findOrFail($id);

        if (! $this->selectedCategory) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Catégorie introuvable.'
            ]);
            return;
        }

        $this->showModal = true;
    }

    /**
     * Ferme la modal et reset la catégorie sélectionnée.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedCategory = null;
    }

    /**
     * Supprime une catégorie (attention : suppression définitive).
     */
    public function deleteCategory($id): void
    {
        $id = (int) $id;

        $category = ProductCategory::find($id);

        if (! $category) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Catégorie introuvable.'
            ]);
            return;
        }

        try {
            $category->delete();

            // si modal ouvert pour cette catégorie → fermer
            if ($this->selectedCategory?->id === $id) {
                $this->closeModal();
            }

            // refresh pagination
            $this->resetPage();

            session()->flash('success', __("Category deleted successfully."));
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => __("Category deleted successfully.")
            ]);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', $e->getMessage());
        }
    }

    /**
     * Render principal du composant.
     */
        public function render()
        {
            return view('livewire.shop.product-category-list', [
                'categories' => $this->getCategories(),
            ]);
        }

    private function getCategories()
    {
        $cacheKey = 'product_categories_' . md5($this->search);
        return Cache::remember($cacheKey, 60, function () {
            return ProductCategory::query()
                ->when($this->search, function(Builder $query) {
                    $query->where(function(Builder $q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
                })
                ->with('products')
                ->latest()
                ->paginate(20);
        });
    }
}
