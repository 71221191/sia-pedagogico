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
        Schema::create('teacher_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('people')->onDelete('cascade');
            $table->integer('day_of_week'); // 1=Lunes, 2=Martes... 5=Viernes
            $table->foreignId('time_slot_id')->constrained('time_slots')->onDelete('cascade');

            // Estado: true = Disponible, false = NO Disponible (Zona Roja)
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            // Evitamos duplicados: Un profe no puede tener dos estados para la misma hora/día
            $table->unique(['teacher_id', 'day_of_week', 'time_slot_id'], 'teacher_slot_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_availabilities');
    }
};
