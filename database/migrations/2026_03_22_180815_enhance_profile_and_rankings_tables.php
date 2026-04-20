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
        // 1. Ampliamos la información personal en la tabla people
        Schema::table('people', function (Blueprint $table) {
            // Ponemos el email personal después del DNI o al final
            $table->string('personal_email')->nullable()->after('dni');

            // El teléfono fijo después del teléfono móvil (que sí existe)
            $table->string('phone_fijo')->nullable()->after('phone');

            // Referencia de dirección después de la dirección
            $table->string('address_reference')->nullable()->after('address');

            // Localidad después del ubigeo de residencia
            $table->string('locality')->nullable()->after('ubigeo_residence_id');

            // La foto oficial la ponemos al final
            $table->string('official_photo_path')->nullable();
        });

        // 2. La tabla de ranking se queda IGUAL (esa no dio error)
        Schema::create('academic_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people');
            $table->foreignId('academic_period_id')->constrained('academic_periods');
            $table->decimal('weighted_average', 5, 4);
            $table->integer('position');
            $table->integer('total_students');
            $table->timestamp('calculated_at');
            $table->timestamps();
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
