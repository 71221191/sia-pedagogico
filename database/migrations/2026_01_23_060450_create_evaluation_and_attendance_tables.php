<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_scales', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Logrado, 20, etc.
            $table->decimal('numeric_equivalent', 5, 2);
            $table->foreignId('study_plan_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Notas por competencia (DCBN Nuevo)
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_detail_id')->constrained();
            $table->integer('competency_id'); // Referencia lógica al catálogo
            $table->foreignId('grade_scale_id')->constrained();
            $table->text('comment')->nullable();
            $table->foreignId('registered_by')->constrained('users');
            $table->timestamp('registered_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos las tablas creadas en orden inverso
        Schema::dropIfExists('grades');
        Schema::dropIfExists('grade_scales');
    }
};
