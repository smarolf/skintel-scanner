<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'original_price',
        'rating',
        'reviews',
        'category',
        'benefits',
        'concern_type',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:1',
        'reviews' => 'integer',
        'benefits' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by concern type.
     */
    public function scopeForConcern($query, $concernType)
    {
        return $query->where('concern_type', $concernType);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeInCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the formatted price with currency.
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get the formatted original price with currency.
     */
    public function getFormattedOriginalPriceAttribute()
    {
        return $this->original_price ? '$' . number_format($this->original_price, 2) : null;
    }

    /**
     * Check if product has a discount.
     */
    public function getHasDiscountAttribute()
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    /**
     * Get the discount percentage.
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) {
            return 0;
        }

        return round((($this->original_price - $this->price) / $this->original_price) * 100);
    }
    /**
     * Get the product recommendations for this product.
     */
    public function productRecommendations(): HasMany
    {
        return $this->hasMany(ProductRecommendation::class);
    }
}
