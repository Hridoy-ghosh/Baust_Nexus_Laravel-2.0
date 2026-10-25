<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_session_id')->nullable()->index();
            $table->string('guest_name')->default('Guest');
            $table->string('order_token')->nullable()->unique();
            $table->string('delivery_option');
            $table->string('floor')->nullable();
            $table->string('table_number')->nullable();
            $table->string('payment_method');
            $table->text('special_instructions')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('vat', 10, 2);
            $table->decimal('service_charge', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_orders');
    }
};
