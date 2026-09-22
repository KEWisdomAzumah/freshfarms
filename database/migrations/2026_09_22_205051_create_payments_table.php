<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');

            $table->foreignId('order_id')
                ->constrained('orders', 'order_id')
                ->cascadeOnDelete();

            $table->string('provider', 100)->nullable();
            $table->string('payment_method', 30);

            $table->string('provider_reference', 255)
                ->nullable();

            $table->decimal('amount', 14, 2);

            $table->char('currency', 3)
                ->default('GHS');

            $table->string('payment_status', 20)
                ->default('initiated');

            $table->text('failure_reason')->nullable();
            $table->timestampTz('paid_at')->nullable();

            $table->timestampsTz();

            $table->index(
                'order_id',
                'idx_payments_order'
            );
        });

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT chk_payments_method
            CHECK (
                payment_method IN (
                    'mobile_money',
                    'card'
                )
            )
        ");

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT chk_payments_status
            CHECK (
                payment_status IN (
                    'initiated',
                    'pending',
                    'successful',
                    'failed',
                    'cancelled',
                    'refunded'
                )
            )
        ");

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT chk_payments_amount
            CHECK (amount > 0)
        ");

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT chk_payments_currency
            CHECK (currency ~ '^[A-Z]{3}$')
        ");

        DB::statement("
            CREATE UNIQUE INDEX uq_payments_provider_reference
            ON payments (provider_reference)
            WHERE provider_reference IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};