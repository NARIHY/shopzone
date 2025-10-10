<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Remplace par un vrai contrôle d’accès si nécessaire
        return true;
    }

    /**
     * Préparer les données avant validation
     */
    protected function prepareForValidation()
{
    // Normaliser les prix
    if ($this->has('price_raw')) {
        $this->merge([
            'price' => $this->normalizePrice($this->input('price_raw', $this->price)),
        ]);
    }

    if ($this->has('discount_price_raw')) {
        $this->merge([
            'discount_price' => $this->normalizePrice($this->input('discount_price_raw', $this->discount_price)),
        ]);
    }

    // Décoder media JSON si nécessaire
    if ($this->has('media')) {
        $media = $this->input('media');
        $decoded = [];

        foreach ($media as $item) {
            if (is_string($item) && str_starts_with($item, '[')) {
                $json = json_decode($item, true);
                if (is_array($json)) {
                    $decoded = array_merge($decoded, $json); // OK, c'est un tableau
                } elseif ($json !== null) {
                    $decoded[] = $json; // si int ou autre, on l'ajoute directement
                }
            } else {
                $decoded[] = $item;
            }
        }

        // S’assurer que ce soit des entiers
        $decoded = array_map('intval', $decoded);

        $this->merge([
            'media' => $decoded,
        ]);
    }
}


    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'slug'                => ['required', 'string', 'max:255', 'unique:products,slug'],
            'description'         => ['nullable', 'string'],
            'price'               => ['required', 'numeric', 'min:0'],
            'discount_price'      => ['nullable', 'numeric', 'lt:price'],
            'stock'               => ['required', 'integer', 'min:0'],
            'sku'                 => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'is_active'           => ['boolean'],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'media'               => ['required', 'array', 'min:1'],
            'media.*'             => ['integer', 'exists:media,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'The product name is required.',
            'slug.required'           => 'The slug is mandatory.',
            'slug.unique'             => 'This slug already exists.',
            'price.required'          => 'The price is mandatory.',
            'discount_price.lt'       => 'The promotional price must be lower than the regular price.',
            'product_category_id.exists' => 'The selected category is invalid.',
            'media.required'          => 'Please select at least one media.',
            'media.*.exists'          => 'One of the selected media is invalid.',
        ];
    }

    /**
     * Convertit une valeur en float en nettoyant les espaces et les virgules
     */
    private function normalizePrice($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Supprime espaces et remplace virgules par points
        $clean = str_replace([',', ' '], ['.', ''], $value);
        return (float) preg_replace('/[^\d.]/', '', $clean);
    }
}
