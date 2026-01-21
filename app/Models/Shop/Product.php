<?php

namespace App\Models\Shop;

use App\Models\Files\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\Shop\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'sku',
        'is_active',
        'product_category_id',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    /* ----------------------------------------
    |  RELATIONSHIPS
    |---------------------------------------- */

    /**
     * A product can have multiple media files (images, videos, etc.)
     */
    public function media()
    {
        return $this->belongsToMany(Media::class, 'media_product', 'product_id', 'media_id')
            ->withTimestamps();
    }

    /**
     * A product belongs to a single category
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /* ----------------------------------------
    |  ACCESSORS & HELPERS
    |---------------------------------------- */

    /**
     * Returns the main (first) media file of the product
     */
    public function mainImage()
    {
        return $this->media()->first();
    }

    /**
     * Returns the final price after applying any discount
     */
    public function finalPrice(): float
    {
        return max(0, $this->price - ($this->discount_price ?? 0));
    }

    /**
     * Checks whether the product is in stock
     */
    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    /* ----------------------------------------
    |  MUTTATORS
    |---------------------------------------- */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ucfirst($value),
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper(str_replace(' ', '-', $value)),
        );
    }

    protected function sku(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value),
        );
    }
}
