<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_staff', function (Blueprint $table) {
            $table->id();
            $table->string('unit', 20);
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('duty_hours', 100);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['unit', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_staff');
    }
};
