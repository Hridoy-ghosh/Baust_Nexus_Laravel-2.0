<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->string('category'); // Computer Science, Electrical, Mechanical, Basic Science, Literature
            $table->string('department')->default('All');
            $table->integer('total_copies')->default(5);
            $table->integer('available_copies')->default(5);
            $table->string('shelf_location')->default('Rack A-3');
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_books');
    }
};
