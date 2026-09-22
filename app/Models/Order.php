<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'delivery_name',
        'delivery_phone',
        'delivery_address',
        'delivery_region',
        'delivery_district',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'buyer_id',
            'user_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'order_id',
            'order_id'
        );
    }

    public function payment(): HasMany
    {
        return $this->hasMany(
            Payment::class,
            'order_id',
            'order_id'
        );
    }

}
