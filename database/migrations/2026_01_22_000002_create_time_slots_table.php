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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->enum('shift', ['mañana', 'tarde']); // Turno
            $table->time('start_time'); // Hora inicio
            $table->time('end_time');   // Hora fin
            $table->integer('order');    // Bloque 1, 2, 3...
            $table->boolean('is_break')->default(false); // Para marcar el recreo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
