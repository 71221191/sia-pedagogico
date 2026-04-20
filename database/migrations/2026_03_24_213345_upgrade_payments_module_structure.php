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
        // 1. Creamos la tabla de Conceptos (TUPA)
        Schema::create('payment_concepts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable(); // La partida (ej: 1.3.2 3.1 7)
            $table->string('name'); // El nombre del concepto
            $table->decimal('amount', 10, 2); // El precio oficial
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Ajustamos la tabla de pagos actual
        Schema::table('payments', function (Blueprint $table) {
            // Añadimos el vínculo al TUPA
            $table->foreignId('payment_concept_id')->nullable()->after('person_id')->constrained('payment_concepts');

            // Campo para que el Tesorero suba la boleta del otro sistema
            $table->string('official_receipt_path')->nullable()->after('voucher_image_path');

            // Borrado lógico para auditoría (no se borra nada de verdad)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
