<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_borrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('library_book_id')->constrained('library_books')->onDelete('cascade');
            $table->string('token_number')->unique();
            $table->string('status')->default('pending'); // pending, borrowed, returned, cancelled
            $table->date('issue_date')->nullable();
            $table->date('return_due_date')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_borrows');
    }
};
