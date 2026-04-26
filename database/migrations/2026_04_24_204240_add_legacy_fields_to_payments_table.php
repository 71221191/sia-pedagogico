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
        Schema::table('payments', function (Blueprint $table) {
            // Campos para la boleta del sistema anterior (Serie y Número)
            $table->string('external_serie')->nullable()->after('operation_number');
            $table->string('external_number')->nullable()->after('external_serie');

            // Marca para saber si es un pago migrado masivamente
            $table->boolean('is_imported')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['external_serie', 'external_number', 'is_imported']);
        });
    }
};
