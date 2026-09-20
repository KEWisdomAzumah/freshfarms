<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');

            $table->foreignId('product_id')
                ->constrained('products', 'product_id')
                ->cascadeOnDelete();

            $table->decimal('quantity_available', 14, 3)
                ->default(0);

            $table->decimal('quantity_reserved', 14, 3)
                ->default(0);

            $table->decimal('minimum_stock_level', 14, 3)
                ->default(0);

            $table->timestampTz('last_restocked_at')
                ->nullable();

            $table->timestampsTz();

            $table->unique(
                'product_id',
                'uq_inventory_product'
            );
        });

        DB::statement("
            ALTER TABLE inventory
            ADD CONSTRAINT chk_inventory_available
            CHECK (quantity_available >= 0)
        ");

        DB::statement("
            ALTER TABLE inventory
            ADD CONSTRAINT chk_inventory_reserved
            CHECK (quantity_reserved >= 0)
        ");

        DB::statement("
            ALTER TABLE inventory
            ADD CONSTRAINT chk_inventory_minimum
            CHECK (minimum_stock_level >= 0)
        ");

        DB::statement("
            ALTER TABLE inventory
            ADD CONSTRAINT chk_inventory_reservation
            CHECK (quantity_reserved <= quantity_available)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};