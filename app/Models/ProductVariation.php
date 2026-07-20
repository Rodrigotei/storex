<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'variation_group_id', 'value', 'additional_price', 'status'])]
class ProductVariation extends Model
{
    public function variationGroup(): BelongsTo
    {
        return $this->belongsTo(VariationGroup::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
