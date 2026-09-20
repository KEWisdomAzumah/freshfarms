<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id('farm_id');

            $table->foreignId('owner_id')
                ->constrained('users', 'user_id');

            $table->string('farm_name', 180);
            $table->text('description')->nullable();

            $table->string('farm_type', 20);

            $table->decimal('farm_size', 12, 3)->nullable();
            $table->string('size_unit', 20)->nullable();
            $table->string('ownership_type', 20)->nullable();

            $table->string('region', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('community', 150)->nullable();

            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();

            $table->string('verification_status', 20)
                ->default('pending');

            $table->text('verification_notes')->nullable();

            $table->unsignedBigInteger('verified_by')->nullable();

            $table->foreign('verified_by')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();

            $table->timestampTz('verified_at')->nullable();

            $table->jsonb('documents')->nullable();

            $table->softDeletesTz();
            $table->timestampsTz();

            $table->index('owner_id', 'idx_farms_owner');

            $table->index(
                ['region', 'district', 'community'],
                'idx_farms_location'
            );

            $table->index(
                'verification_status',
                'idx_farms_verification'
            );
        });

        DB::statement("
            ALTER TABLE farms
            ADD CONSTRAINT chk_farm_type
            CHECK (
                farm_type IN (
                    'crop',
                    'livestock',
                    'mixed',
                    'aquaculture',
                    'service'
                )
            )
        ");

        DB::statement("
            ALTER TABLE farms
            ADD CONSTRAINT chk_farm_size_unit
            CHECK (
                size_unit IS NULL
                OR size_unit IN ('acre', 'hectare')
            )
        ");

        DB::statement("
            ALTER TABLE farms
            ADD CONSTRAINT chk_farm_ownership
            CHECK (
                ownership_type IS NULL
                OR ownership_type IN (
                    'owned',
                    'leased',
                    'family',
                    'community'
                )
            )
        ");

        DB::statement("
            ALTER TABLE farms
            ADD CONSTRAINT chk_farm_verification
            CHECK (
                verification_status IN (
                    'pending',
                    'approved',
                    'rejected',
                    'suspended'
                )
            )
        ");

        DB::statement("
            ALTER TABLE farms
            ADD CONSTRAINT chk_farm_size
            CHECK (
                farm_size IS NULL
                OR farm_size > 0
            )
        ");

        DB::statement("
            ALTER TABLE farms
            ADD CONSTRAINT chk_farm_latitude
            CHECK (
                latitude IS NULL
                OR latitude BETWEEN -90 AND 90
            )
        ");

        DB::statement("
            ALTER TABLE farms
            ADD CONSTRAINT chk_farm_longitude
            CHECK (
                longitude IS NULL
                OR longitude BETWEEN -180 AND 180
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farms');
    }
};
