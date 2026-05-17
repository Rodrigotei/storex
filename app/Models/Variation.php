<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variation extends Model
{
    public function variationGroups(): HasMany
    {
        return $this->hasMany(VariationGroup::class);
    }
}
