<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['img', 'product_id'])]
class ProductImage extends Model
{
    protected $connection = 'store';
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }    
}
