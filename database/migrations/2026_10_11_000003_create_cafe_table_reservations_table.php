<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe_table_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('floor');
            $table->string('table_number');
            $table->foreignId('cafe_order_id')->nullable()->constrained('cafe_orders')->nullOnDelete();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
            $table->unique(['floor', 'table_number']);
        });

        $now = now();
        $slots = [];

        foreach (['Ground Floor', 'Outdoor Seating'] as $floor) {
            for ($table = 1; $table <= 12; $table++) {
                $slots[] = [
                    'floor' => $floor,
                    'table_number' => 'Table '.$table,
                    'cafe_order_id' => null,
                    'expires_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('cafe_table_reservations')->insert($slots);
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_table_reservations');
    }
};
