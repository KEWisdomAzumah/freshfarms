<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'farm_id';

    protected $fillable = [
        'owner_id',
        'farm_name',
        'description',
        'farm_type',
        'farm_size',
        'size_unit',
        'ownership_type',
        'region',
        'district',
        'community',
        'latitude',
        'longitude',
        'documents',
    ];

    protected function casts(): array
    {
        return [
            'farm_size' => 'decimal:3',
            'latitude' => 'decimal:6',
            'longitude' => 'decimal:6',
            'documents' => 'array',
            'verified_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'owner_id',
            'user_id'
        );
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by',
            'user_id'
        );
    }

    public function products():HasMany{
        return $this -> hasMany(
            Product::class, 'farm_id', 'farm_id');
    }
}