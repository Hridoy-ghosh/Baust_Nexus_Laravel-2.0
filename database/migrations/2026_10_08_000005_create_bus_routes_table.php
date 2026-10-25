<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_name'); // e.g. Saidpur - Campus Route 1
            $table->string('route_code'); // e.g. R-01
            $table->text('description')->nullable();
            $table->string('morning_schedule'); // e.g. 7:30 AM
            $table->string('afternoon_schedule'); // e.g. 2:15 PM & 4:45 PM
            $table->text('pickup_points'); // comma separated or text
            $table->string('bus_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('status')->default('On Time'); // On Time, Delayed, Maintenance
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_routes');
    }
};
