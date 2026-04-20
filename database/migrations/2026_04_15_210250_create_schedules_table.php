<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('teacher_id')->constrained('people'); // El profe que dicta
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms'); // El aula (puede ser null al inicio)
            $table->foreignId('time_slot_id')->constrained('time_slots');
            $table->integer('day_of_week'); // 1=Lu, 2=Ma... 5=Vi

            $table->timestamps();

            // REGLAS DE ORO (Índices Únicos para evitar choques desde la DB)
            // Un profesor no puede estar en dos sitios a la misma hora
            $table->unique(['academic_period_id', 'teacher_id', 'day_of_week', 'time_slot_id'], 'teacher_no_clash');

            // Un aula no puede estar ocupada por dos clases a la misma hora
            // (Añadiremos el academic_period_id para filtrar por semestre)
            $table->foreignId('academic_period_id')->constrained('academic_periods');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
