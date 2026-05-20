<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. UNIDADES ACADÉMICAS (Aquí está la flexibilidad: cada curso puede tener N unidades)
        Schema::create('academic_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Ej: "Unidad I: Fundamentos"
            $table->integer('order')->default(1); // Para saber cuál va primero
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        // 2. RECURSOS DE APRENDIZAJE (Material que el alumno descarga)
        Schema::create('learning_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_unit_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['file', 'link']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); // Para PDFs, PPTs
            $table->string('url')->nullable();      // Para links de YouTube o webs
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        // 3. TAREAS (Actividades calificables)
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_unit_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->dateTime('due_date');      // Fecha límite recomendada
            $table->dateTime('closing_date');  // Fecha de cierre total (candado)
            $table->decimal('max_score', 5, 2)->default(20.00);
            $table->string('allowed_formats')->default('pdf,docx'); // Restricción de formato
            $table->integer('max_file_size_kb')->default(5120); // 5MB por defecto
            $table->timestamps();
        });

        // 4. ENTREGAS DE ALUMNOS (Submissions)
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('person_id')->constrained('people'); // El alumno
            $table->string('file_path');
            $table->dateTime('submitted_at');
            $table->decimal('score', 5, 2)->nullable(); // Nota que pone el profe
            $table->text('teacher_feedback')->nullable(); // Comentario pedagógico
            $table->enum('status', ['sent', 'graded', 'late'])->default('sent');
            $table->timestamps();
        });

        // 5. FOROS (Debate pedagógico)
        Schema::create('learning_forums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_unit_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_forums');
        Schema::dropIfExists('task_submissions');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('learning_resources');
        Schema::dropIfExists('academic_units');
    }
};
