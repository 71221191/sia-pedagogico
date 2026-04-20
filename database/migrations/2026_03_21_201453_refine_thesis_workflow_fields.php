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
            // 1. Campos de Programación (Antes de sustentar)
            $table->date('scheduled_date')->nullable()->after('status');
            $table->time('scheduled_time')->nullable()->after('scheduled_date');
            $table->string('scheduled_location')->nullable()->after('scheduled_time'); // Aula o Virtual

            // 2. Correlativo automático (Solo número, el año lo sacamos por código)
            $table->integer('office_number')->nullable()->after('document_correlative');

            // 3. Ajustamos el status para que sea un flujo real
            // registered -> assigned -> scheduled -> defended -> closed
            $table->string('status')->default('registered')->change();
        });
    }

    public function down()
    {
        Schema::table('thesis_projects', function (Blueprint $table) {
            $table->dropColumn(['scheduled_date', 'scheduled_time', 'scheduled_location', 'office_number']);
        });
    }
    
};
