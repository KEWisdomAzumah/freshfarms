<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');

            $table->foreignId('order_id')
                ->constrained('orders', 'order_id')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->nullOnDelete();

            $table->unsignedBigInteger('farm_id')->nullable();
            $table->foreign('farm_id')
                ->references('farm_id')
                ->on('farms')
                ->nullOnDelete();

            $table->string('product_name_snapshot', 180);
            $table->string('unit_snapshot', 30);

            $table->decimal('quantity', 14, 3);
            $table->decimal('price_at_time', 14, 2);
            $table->decimal('line_total', 14, 2);

            // The agreed schema has created_at only.
            $table->timestampTz('created_at')->useCurrent();

            $table->index(
                'order_id',
                'idx_order_items_order'
            );
        });

        DB::statement("
            ALTER TABLE order_items
            ADD CONSTRAINT chk_order_items_quantity
            CHECK (quantity > 0)
        ");

        DB::statement("
            ALTER TABLE order_items
            ADD CONSTRAINT chk_order_items_price
            CHECK (price_at_time >= 0)
        ");

        DB::statement("
            ALTER TABLE order_items
            ADD CONSTRAINT chk_order_items_total
            CHECK (
                line_total = ROUND(quantity * price_at_time, 2)
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

?>