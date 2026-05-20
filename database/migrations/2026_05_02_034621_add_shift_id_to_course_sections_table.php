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
        Schema::table('course_sections', function (Blueprint $table) {
            // Añadimos la relación con la tabla shifts (turnos)
            // Lo ponemos después de academic_period_id para mantener orden
            $table->foreignId('shift_id')
                  ->nullable()
                  ->after('academic_period_id')
                  ->constrained('shifts')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_sections', function (Blueprint $table) {
            // Eliminamos la clave foránea y la columna
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
        });
    }
};
