<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'name', 'slug', 'phone', 'description', 'delivery_fee', 'img'])]
class Store extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(StoreAddress::class);
    }

    public function whatsappUrl(?string $message = null): ?string
    {
        $phone = preg_replace('/\D+/', '', (string) $this->phone);

        if (in_array(strlen($phone), [10, 11], true)) {
            $phone = '55'.$phone;
        }

        if (! in_array(strlen($phone), [12, 13], true) || ! str_starts_with($phone, '55')) {
            return null;
        }

        $url = "https://wa.me/{$phone}";

        return $message ? $url.'?text='.urlencode($message) : $url;
    }
}
