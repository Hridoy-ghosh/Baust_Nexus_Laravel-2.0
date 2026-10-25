<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // breakfast, lunch, snacks, beverages, combos
            $table->decimal('price', 8, 2);
            $table->decimal('discount_price', 8, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_special_offer')->default(false);
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_items');
    }
};
