<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CafeTableReservation extends Model
{
    protected $fillable = [
        'floor',
        'table_number',
        'cafe_order_id',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(CafeOrder::class, 'cafe_order_id');
    }
}
