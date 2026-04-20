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
        Schema::table('courses', function (Blueprint $table) {
            // 1. Añadimos el Slug (Índice para búsquedas ultra rápidas)
            if (!Schema::hasColumn('courses', 'slug')) {
                $table->string('slug')->nullable()->after('name')->index();
            }

            // 2. Añadimos el Componente (FG, FPI, FE, Electivo)
            if (!Schema::hasColumn('courses', 'component')) {
                $table->string('component', 10)->nullable()->after('type');
            }

            // 3. Añadimos Horas Totales (H)
            if (!Schema::hasColumn('courses', 'hours_total')) {
                $table->integer('hours_total')->default(0)->after('credits');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
        });
    }
};
