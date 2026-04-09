<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name','tenant_id','description','price', 'promotional_price','status', 'duration'])]
class Service extends Model
{
    public function serviceImages(): HasMany
    {
        return $this->hasMany(ServiceImage::class);
    }
}
