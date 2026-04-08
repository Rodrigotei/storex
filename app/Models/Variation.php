<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variation extends Model
{
    public function productVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }
}
