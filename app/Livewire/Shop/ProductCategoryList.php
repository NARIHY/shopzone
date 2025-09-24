<?php

namespace App\Livewire\Shop;

use App\Models\Shop\ProductCategory;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
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
    ];

    /**
     * Quand on tape dans la recherche, on revient à la 1ère page.
     */
    public function updatingSearch(): void
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
        $this->selectedCategory = ProductCategory::find($id);

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

            session()->flash('message', __("La catégorie a été supprimée."));
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => __("La catégorie a été supprimée.")
            ]);
        } catch (\Throwable $e) {
            report($e);

            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Impossible de supprimer la catégorie.'
            ]);
        }
    }

    /**
     * Render principal du composant.
     */
    public function render()
    {
       $categories = ProductCategory::orderBy('created_at', 'desc')
            ->where('name', 'like', "%{$this->search}%")
            ->paginate(10);

        return view('livewire.shop.product-category-list', [
            'categories' => $categories,
        ]);
    }
}
