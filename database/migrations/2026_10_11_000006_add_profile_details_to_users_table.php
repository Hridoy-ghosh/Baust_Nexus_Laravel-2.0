<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender', 32)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('blood_group', 3)->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->string('address', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'date_of_birth',
                'blood_group',
                'emergency_contact',
                'address',
            ]);
        });
    }
};
