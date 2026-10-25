<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('resource_type'); // lecture_note, lab_manual, question, assignment, study_material
            $table->string('department'); // CSE, EEE, ME, CE, IPE, BBA, English
            $table->string('level'); // Level 1, Level 2, Level 3, Level 4
            $table->string('term'); // Term I, Term II
            $table->string('course_code'); // e.g., CSE-3201
            $table->string('course_title'); // e.g., Web Engineering
            $table->string('file_path')->nullable();
            $table->string('external_link')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedInteger('downloads_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_resources');
    }
};
