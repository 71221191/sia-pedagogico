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
        Schema::table('people', function (Blueprint $table) {
            // Campos para el Legajo Docente
            $table->string('employment_condition')->nullable()->after('gender'); // Nombrado / Contratado
            $table->string('academic_degree')->nullable()->after('employment_condition'); // Magíster, Doctor, etc.
            $table->string('professional_category')->nullable()->after('academic_degree'); // Principal, Asociado...
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            //
        });
    }
};
