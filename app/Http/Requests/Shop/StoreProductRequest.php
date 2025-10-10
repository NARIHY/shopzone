<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Si seuls les admins peuvent créer des produits, remplace par un vrai contrôle d’accès.
        // Exemple : return $this->user()?->can('create', Product::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'slug'                => ['required', 'string', 'max:255', 'unique:products,slug'],
            'description'         => ['nullable', 'string'],
            'price'               => ['required'],
            'discount_price'      => ['nullable', 'lt:price'],
            'stock'               => ['required', 'integer', 'min:0'],
            'sku'                 => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'is_active'           => ['boolean'],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'media'=> ['array', 'required'],
            'media.*' => ['exists:media,id', 'required', 'min:1']
        ];
    }

    /**
     * Customize validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required'           => 'The product name is required.',
            'slug.required'           => 'The slug is mandatory.',
            'slug.unique'             => 'This slug already exists.',
            'price.required'          => 'The price is mandatory.',
            'discount_price.lt'       => 'The promotional price must be lower than the regular price.',
            'product_category_id.exists' => 'The selected category is invalid.',
        ];
    }
}
