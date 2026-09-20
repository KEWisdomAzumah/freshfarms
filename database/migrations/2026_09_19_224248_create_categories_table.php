<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');

            $table->unsignedBigInteger('parent_category_id')->nullable();

            $table->string('category_name', 120);
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);

            $table->timestampsTz();

            $table->foreign('parent_category_id')
                ->references('category_id')
                ->on('categories')
                ->nullOnDelete();

            $table->unique(
                'category_name',
                'uq_categories_name'
            );
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
