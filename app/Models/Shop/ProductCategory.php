<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    /** @use HasFactory<\Database\Factories\Shop\ProductCategoryFactory> */
    use HasFactory;
    protected $table = 'product_categories';    
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * A category can have many products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }
}
