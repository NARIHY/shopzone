<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autoriser l'accès (remplace par Policy si besoin)
        return true;
    }

    /**
     * Définition des règles de validation pour la mise à jour d’un produit
     */
    public function rules(): array
    {
        $productId = $this->route('product')?->id; // Récupère l’ID du produit depuis la route

        return [
            'name'                => ['required', 'string', 'max:255'],
            'slug'                => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($productId),
            ],
            'description'         => ['nullable', 'string'],
            'price'               => ['required', 'numeric', 'min:0'],
            'discount_price'      => ['nullable', 'numeric', 'lt:price'],
            'stock'               => ['required', 'integer', 'min:0'],
            'sku'                 => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            'is_active'           => ['boolean'],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'media'               => ['required'],
            'media.*'             => [],
        ];
    }

    /**
     * Messages d’erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'name.required'              => 'The product name is required.',
            'slug.required'              => 'The slug is mandatory.',
            'slug.unique'                => 'This slug already exists.',
            'price.required'             => 'The price is mandatory.',
            'price.numeric'              => 'The price must be a numeric value.',
            'discount_price.lt'          => 'The promotional price must be lower than the regular price.',
            'stock.required'             => 'Stock is required.',
            'stock.integer'              => 'Stock must be an integer.',
            'stock.min'                  => 'Stock must be zero or greater.',
            'sku.unique'                 => 'This SKU already exists.',
            'product_category_id.exists' => 'The selected category is invalid.',
            'media.required'             => 'Please select at least one media.',
            'media.array'                => 'Media must be an array.',
            'media.*.exists'             => 'One of the selected media is invalid.',
        ];
    }

    /**
     * Convertit une valeur de prix en float en nettoyant les caractères inutiles
     */
    private function normalizePrice(mixed $value): ?float
    {
        if (empty($value)) {
            return null;
        }

        // Supprime les espaces, remplace les virgules par des points, et enlève les caractères non numériques
        $normalized = str_replace(',', '.', $value);
        $normalized = preg_replace('/[^\d.]/', '', $normalized);

        return (float) $normalized;
    }
}
