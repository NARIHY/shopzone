<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all users to create products; adjust as needed for auth
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                => ['required','string','max:255'],
            'slug'                => ['required','string','max:255','unique:products,slug'],
            'description'         => ['nullable','string'],
            'price'               => ['required','numeric','min:0'],
            'discount_price'      => ['nullable','numeric','lt:price'],
            'stock'               => ['required','integer','min:0'],
            'sku'                 => ['nullable','string','max:100','unique:products,sku'],
            'is_active'           => ['boolean'],
            'product_category_id' => ['nullable','exists:product_categories,id'],
            'media'               => ['required'],
            'media.*'             => [],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'The product name is required.',
            'slug.required'           => 'The slug is mandatory.',
            'slug.unique'             => 'This slug already exists.',
            'price.required'          => 'The price is mandatory.',
            'discount_price.lt'       => 'The discount price must be lower than the regular price.',
            'product_category_id.exists' => 'The selected category is invalid.',
            'media.required'          => 'Please select at least one media.',
            'media.*.exists'          => 'One of the selected media is invalid.',
        ];
    }
}
