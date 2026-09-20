<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');

            $table->foreignId('farm_id')
                ->constrained('farms', 'farm_id');

            $table->foreignId('category_id')
                ->constrained('categories', 'category_id');

            $table->string('product_name', 180);
            $table->string('variety', 120)->nullable();

            $table->text('description')->nullable();
            $table->text('quality_notes')->nullable();

            $table->string('product_type', 30);
            $table->string('unit_of_measure', 20);

            $table->decimal('price_per_unit', 14, 2)
                ->default(0);

            $table->char('currency', 3)
                ->default('GHS');

            $table->decimal('minimum_order_quantity', 14, 3)
                ->default(1);

            $table->date('harvest_date')->nullable();
            $table->date('expiry_date')->nullable();

            $table->jsonb('photos')->nullable();

            $table->string('product_status', 30)
                ->default('draft');

            $table->unsignedBigInteger('views_count')
                ->default(0);

            $table->softDeletesTz();
            $table->timestampsTz();

            $table->index(
                ['farm_id', 'product_status'],
                'idx_products_farm_status'
            );

            $table->index(
                ['category_id', 'product_status'],
                'idx_products_category_status'
            );

            $table->index(
                'product_name',
                'idx_products_name'
            );
        });

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_type
            CHECK (
                product_type IN (
                    'crop',
                    'animal',
                    'animal_product',
                    'fish',
                    'farm_input',
                    'service'
                )
            )
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_unit
            CHECK (
                unit_of_measure IN (
                    'gram',
                    'kilogram',
                    'tonne',
                    'crate',
                    'basket',
                    'piece',
                    'litre',
                    'animal',
                    'hour',
                    'service'
                )
            )
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_status
            CHECK (
                product_status IN (
                    'draft',
                    'available',
                    'out_of_stock',
                    'suspended',
                    'discontinued'
                )
            )
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_price
            CHECK (price_per_unit >= 0)
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_min_order
            CHECK (minimum_order_quantity > 0)
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_currency
            CHECK (currency ~ '^[A-Z]{3}$')
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_dates
            CHECK (
                expiry_date IS NULL
                OR harvest_date IS NULL
                OR expiry_date >= harvest_date
            )
        ");

        DB::statement("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_views
            CHECK (views_count >= 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};