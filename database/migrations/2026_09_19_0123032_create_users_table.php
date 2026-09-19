<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');

            $table->foreignId('role_id')
                ->constrained('roles', 'role_id');

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255);
            $table->string('phone', 30)->nullable();
            $table->string('password_hash', 255);

            $table->text('profile_photo_url')->nullable();

            $table->string('region', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('community', 150)->nullable();

            $table->string('account_status', 20)
                ->default('pending');

            $table->timestampTz('email_verified_at')->nullable();
            $table->timestampTz('phone_verified_at')->nullable();
            $table->timestampTz('last_login_at')->nullable();

            $table->smallInteger('failed_login_attempts')
                ->default(0);

            $table->timestampTz('locked_until')->nullable();

            $table->softDeletesTz();
            $table->timestampsTz();

            $table->unique('phone', 'uq_users_phone');

            $table->index(
                ['role_id', 'account_status'],
                'idx_users_role_status'
            );

            $table->index(
                ['region', 'district', 'community'],
                'idx_users_location'
            );
        });

        /*
         * PostgreSQL-specific constraints and indexes.
         * These MUST execute after the users table exists.
         */

        DB::statement(
            'CREATE UNIQUE INDEX uq_users_email ON users (LOWER(email))'
        );

        DB::statement("
            ALTER TABLE users
            ADD CONSTRAINT chk_users_status
            CHECK (
                account_status IN (
                    'pending',
                    'active',
                    'suspended',
                    'disabled'
                )
            )
        ");

        DB::statement("
            ALTER TABLE users
            ADD CONSTRAINT chk_users_failed_logins
            CHECK (
                failed_login_attempts >= 0
                AND failed_login_attempts <= 20
            )
        ");

        /*
         * Laravel password reset infrastructure.
         */

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        /*
         * Laravel database session infrastructure.
         */

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};