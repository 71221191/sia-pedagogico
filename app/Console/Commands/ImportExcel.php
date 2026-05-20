<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LegacyPaymentsImport;
use App\Imports\ActiveStudentsImport;
use App\Imports\TeachersImport;
use App\Imports\CoursesImport;

class ImportExcel extends Command
{
    protected $signature = 'app:import {file} {type}';
    protected $description = 'Importación masiva con barra de progreso';

    public function handle()
    {
        $file = $this->argument('file');
        $type = $this->argument('type');
        $filePath = storage_path('app/' . $file);

        if (!file_exists($filePath)) {
            $this->error("Archivo no encontrado.");
            return;
        }

        $this->info("Iniciando importación de {$type}...");

        // Creamos la barra de progreso (la iniciaremos en el Importer)
        $importId = uniqid();

        // Pasamos ESTE comando ($this) al importador para que pueda dibujar la barra
        $import = match ($type) {
            'payments' => new LegacyPaymentsImport($file, $this), // Le pasamos $this
            'students' => new ActiveStudentsImport($importId, $file),
            'teachers' => new TeachersImport($file),
            'courses'  => new CoursesImport($file),
            default    => null,
        };

        try {
            Excel::import($import, $filePath);

            $res = $import->getReporte();
            $this->newline();
            $this->table(
                ['Creados', 'Actualizados', 'Omitidos', 'Errores'],
                [[$res['creados'] ?? 0, $res['actualizados'] ?? 0, $res['omitidos'] ?? 0, $res['errores_count'] ?? 0]]
            );

            $this->info("¡Importación terminada con éxito!"); // Corregido success por info

        } catch (\Exception $e) {
            $this->error("ERROR: " . $e->getMessage());
        }
    }
}
