<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('query_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('query_post_id')->constrained('query_posts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_accepted_answer')->default(false);
            $table->string('role_badge')->nullable(); // Teacher, Senior Student L4T2, Admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('query_replies');
    }
};
