<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CafeOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_session_id',
        'guest_name',
        'order_token',
        'delivery_option',
        'floor',
        'table_number',
        'payment_method',
        'payment_phone',
        'payment_transaction_id',
        'special_instructions',
        'coupon_code',
        'subtotal',
        'vat',
        'service_charge',
        'discount',
        'total_amount',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CafeOrderItem::class);
    }
}
