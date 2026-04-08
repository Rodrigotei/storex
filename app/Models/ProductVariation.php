<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'variation_id', 'value', 'tenant_id'])]
class ProductVariation extends Model
{

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
