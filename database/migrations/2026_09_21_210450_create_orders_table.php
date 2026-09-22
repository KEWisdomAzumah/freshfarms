<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('order_number', 60);

            $table->foreignId('buyer_id')
                ->constrained('users', 'user_id');

            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('delivery_fee', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);

            $table->char('currency', 3)->default('GHS');

            $table->string('order_status', 30)
                ->default('pending');
            $table->string('payment_status', 20)
                ->default('unpaid');

            $table->string('delivery_name', 180);
            $table->string('delivery_phone', 30);

            $table->text('delivery_address')->nullable();
            $table->string('delivery_region', 100)->nullable();
            $table->string('delivery_district', 100)->nullable();

            $table->text('cancellation_reason')->nullable();
            $table->timestampTz('cancelled_at')->nullable();

            $table->timestampsTz();

            $table->unique(
                'order_number',
                'uq_orders_number'
            );

            $table->index(
                'order_status',
                'idx_orders_status'
            );
        });

        DB::statement("
            CREATE INDEX idx_orders_buyer_created
            ON orders (buyer_id, created_at DESC)
        ");

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT chk_orders_status
            CHECK (
                order_status IN (
                    'pending',
                    'confirmed',
                    'processing',
                    'ready for pickup',
                    'shipped',
                    'delivered',
                    'cancelled'
                )
            )
        ");

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT chk_orders_payment_status
            CHECK (
                payment_status IN (
                    'unpaid',
                    'pending',
                    'paid',
                    'refunded',
                    'failed'
                )
            )
        ");

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT chk_orders_subtotal
            CHECK (subtotal >= 0)
        ");

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT chk_orders_delivery_fee
            CHECK (delivery_fee >= 0)
        ");

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT chk_orders_total
            CHECK (total_amount = subtotal + delivery_fee)
        ");

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT chk_orders_currency
            CHECK (currency ~ '^[A-Z]{3}$')
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

?>