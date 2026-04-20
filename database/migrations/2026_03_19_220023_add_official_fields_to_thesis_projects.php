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
        Schema::table('thesis_projects', function (Blueprint $table) {
            // Campos requeridos por los nuevos formatos del MINEDU
            $table->string('type_of_research')->nullable()->after('research_line'); // Ej: Pre-experimental
            $table->string('promotion_year', 4)->nullable()->after('type_of_research'); // Ej: 2023
            $table->string('specialty_resolution')->nullable(); // Ej: RVM 204-2019-MINEDU
            $table->string('document_correlative')->nullable(); // Para el N° de Oficio (Ej: 099-2025)
        });
    }

    public function down()
    {
        Schema::table('thesis_projects', function (Blueprint $table) {
            $table->dropColumn(['type_of_research', 'promotion_year', 'specialty_resolution', 'document_correlative']);
        });
    }
};
