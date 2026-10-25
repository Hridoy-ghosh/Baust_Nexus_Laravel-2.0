<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CafeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'discount_price',
        'description',
        'is_available',
        'is_special_offer',
        'image_url',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_special_offer' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(CafeOrderItem::class);
    }
}
