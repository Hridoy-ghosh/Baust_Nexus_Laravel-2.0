<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('category')->default('academic'); // academic, exam, event, urgent, announcement
            $table->string('target_department')->default('All'); // All, CSE, EEE, etc.
            $table->string('target_level')->default('All'); // All, Level 1, etc.
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->foreignId('published_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('file_attachment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
