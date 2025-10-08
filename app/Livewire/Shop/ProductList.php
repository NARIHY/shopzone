<?php

namespace App\Livewire\Shop;

use App\Models\Shop\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public string $search = '';
 
    protected $queryString = [
        'search' => ['except' => ''], // ne pas inclure dans l'URL si vide
    ];

    public bool $showModal = false;


    public ?Product $selectedProduct = null;


    protected $listeners = [
        'showCategoryModal' => 'openCategoryModal',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCategoryModal($id): void
    {
        $id = (int) $id;
        $this->selectedProduct = Product::findOrFail($id);

        if (! $this->selectedProduct) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Catégorie introuvable.'
            ]);
            return;
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedProduct = null;
    }

    public function deleteProduct($id): void
    {
        $id = (int) $id;

        $product = Product::findOrFail($id);

        if (! $product) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Catégorie introuvable.'
            ]);
            return;
        }

        try {
            $product->delete();

            // si modal ouvert pour cette catégorie → fermer
            if ($this->selectedProduct?->id === $id) {
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

    public function render()
    {
        $products = Product::orderBy('created_at', 'desc')
            ->where('name', 'like', "%{$this->search}%")
            ->paginate(10);
        return view('livewire.shop.product-list', compact('products'));
    }
}
