<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Autoriser l'accès (tu peux remplacer par une vérification via Policy si besoin)
        // Exemple : return $this->user()?->can('update', $this->route('product'));
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
            'price'               => ['required', 'min:0'],
            'discount_price'      => ['nullable', 'lt:price'],
            'stock'               => ['required', 'integer', 'min:0'],
            'sku'                 => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            'is_active'           => ['boolean'],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'media'=> ['array', 'required'],
            'media.*' => ['exists:media,id', 'required', 'min:1']
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required'             => 'The product name is required.',
            'slug.required'             => 'The slug is mandatory.',
            'slug.unique'               => 'This slug already exists.',
            'price.required'            => 'The price is mandatory.',
            'discount_price.lt'         => 'The promotional price must be lower than the regular price.',
            'product_category_id.exists'=> 'The selected category is invalid.',
        ];
    }
}
