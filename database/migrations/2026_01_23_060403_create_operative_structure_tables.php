<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. PERIODOS ACADÉMICOS
        Schema::create('academic_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['planning', 'open', 'closed'])->default('planning');
            $table->timestamps();
        });

        // 2. SECCIONES (Corregido para apuntar a PEOPLE)
        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_period_id')->constrained();
            $table->string('name');
            $table->foreignId('teacher_id')->nullable()->constrained('people'); // <--- SOLUCIÓN CRÍTICA
            $table->integer('vacancy_limit')->default(40);
            $table->boolean('is_closed')->default(false);
            $table->string('acta_number')->nullable();
            $table->timestamp('acta_close_date')->nullable();
            $table->timestamps();
        });

        // 3. HORARIOS (Versión Tanque nivel Senior)
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('teacher_id')->constrained('people');
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms');
            $table->foreignId('time_slot_id')->constrained('time_slots');
            $table->integer('day_of_week');
            $table->foreignId('academic_period_id')->constrained('academic_periods');
            $table->timestamps();

            // Evitar que el profe o el aula choquen a la misma hora
            $table->unique(['academic_period_id', 'teacher_id', 'day_of_week', 'time_slot_id'], 'teacher_no_clash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('course_sections');
        Schema::dropIfExists('academic_periods');
    }
};
