<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_sections', function (Blueprint $table) {
            $table->string('syllabus_path')->nullable()->after('is_closed');
            $table->string('syllabus_name')->nullable()->after('syllabus_path');
        });
    }

    public function down(): void
    {
        Schema::table('course_sections', function (Blueprint $table) {
            $table->dropColumn(['syllabus_path', 'syllabus_name']);
        });
    }
};
