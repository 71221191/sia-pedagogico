<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('legacy_imports', function (Blueprint $table) {
            // Añadimos campos para saber qué se importó y los resultados
            $table->string('import_type')->after('filename'); // students, grades, etc.
            $table->integer('created_count')->default(0)->after('records_processed');
            $table->integer('updated_count')->default(0)->after('created_count');
            $table->integer('omitted_count')->default(0)->after('updated_count');
            $table->integer('error_count')->default(0)->after('omitted_count');

            // Cambiamos errors_log por algo más completo: el detalle de TODAS las filas
            $table->longText('results_details')->nullable()->after('errors_log');
        });
    }

    public function down(): void {
        Schema::table('legacy_imports', function (Blueprint $table) {
            $table->dropColumn(['import_type', 'created_count', 'updated_count', 'omitted_count', 'error_count', 'results_details']);
        });
    }
};
