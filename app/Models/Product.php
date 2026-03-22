<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'category_id', 'price', 'description', 'status'])]
class Product extends Model
{
    protected $connection = 'store';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    } 
    public function productVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    } 
}
