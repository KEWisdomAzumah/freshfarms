<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $primaryKey = 'order_item_id';
    
    protected $guarded = [
        'order_item_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'price_at_time' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            Order::class,
            'order_id',
            'order_id'
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id',
            'product_id'
        );
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(
            Farm::class,
            'farm_id',
            'farm_id'
        );
    }
}

?>