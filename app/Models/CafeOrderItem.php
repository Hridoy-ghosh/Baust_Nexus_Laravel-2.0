<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CafeOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cafe_order_id',
        'cafe_item_id',
        'name',
        'unit_price',
        'quantity',
        'subtotal',
        'item_token',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(CafeOrder::class, 'cafe_order_id');
    }

    public function cafeItem(): BelongsTo
    {
        return $this->belongsTo(CafeItem::class);
    }
}
