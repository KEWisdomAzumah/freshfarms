<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $primaryKey = 'inventory_id';

    protected $fillable = [
        'product_id',
        'quantity_available',
        'minimum_stock_level',
        'last_restocked_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity_available' => 'decimal:3',
            'quantity_reserved' => 'decimal:3',
            'minimum_stock_level' => 'decimal:3',
            'last_restocked_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id', 'product_id'
        );
    }
}