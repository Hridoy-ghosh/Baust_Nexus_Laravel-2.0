<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cafe_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cafe_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->decimal('unit_price', 8, 2);
            $table->unsignedSmallInteger('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->string('item_token')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_order_items');
    }
};
