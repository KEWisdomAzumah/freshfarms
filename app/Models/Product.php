<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'farm_id',
        'category_id',
        'product_name',
        'variety',
        'description',
        'quality_notes',
        'product_type',
        'unit_of_measure',
        'price_per_unit',
        'currency',
        'minimum_order_quantity',
        'harvest_date',
        'expiry_date',
        'photos',
        'product_status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_unit' => 'decimal:2',
            'minimum_order_quantity' => 'decimal:3',
            'harvest_date' => 'date',
            'expiry_date' => 'date',
            'photos' => 'array',
            'views_count' => 'integer',
            'deleted_at' => 'datetime',
        ];
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(
            Farm::class,
            'farm_id',
            'farm_id'
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id',
            'category_id'
        );
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(
            Inventory::class,
            'product_id',
            'product_id'
        );
    }

    public function orderItems():HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'product_id',
            'product_id'
        );
    }

}