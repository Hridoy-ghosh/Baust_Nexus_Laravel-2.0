<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('query_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('category')->default('academic'); // academic, campus, admin, general
            $table->string('department')->default('CSE');
            $table->string('level')->default('All');
            $table->boolean('is_anonymous')->default(false);
            $table->string('status')->default('open'); // open, resolved
            $table->unsignedInteger('replies_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('query_posts');
    }
};
