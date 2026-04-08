<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['store_id', 'street', 'numbre', 'complement', 'neighborhood', 'city', 'state', 'zip_code', 'tenant_id'])]
class StoreAddress extends Model
{
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
