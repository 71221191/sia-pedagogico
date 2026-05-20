<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\LegacyImport;


trait TracksImportResults
{
    // Donde guardaremos el rastro de cada fila
    protected array $rowsDetails = [];

    // Contadores individuales
    protected int $c_created = 0;
    protected int $c_updated = 0;
    protected int $c_omitted = 0;
    protected int $c_errors = 0;

    /**
     * Registra el resultado de una fila específica
     */
    public function recordRowResult(array $rowData, string $status, string $message = '')
    {
        $this->rowsDetails[] = [
            'data' => $rowData, // Toda la fila original del Excel
            'status' => $status, // 'CREADO', 'ACTUALIZADO', 'ERROR', 'OMITIDO'
            'message' => $message
        ];

        // Aumentamos los contadores según el status
        match ($status) {
            'CREADO' => $this->c_created++,
            'ACTUALIZADO' => $this->c_updated++,
            'OMITIDO' => $this->c_omitted++,
            'ERROR' => $this->c_errors++,
        };
    }

    /**
     * Guarda el historial final en la base de datos
     */
    public function saveImportHistory(string $filename, string $type)
    {
        return LegacyImport::create([
            'filename' => $filename,
            'import_type' => $type,
            'records_processed' => $this->c_created + $this->c_updated + $this->c_errors + $this->c_omitted,
            'created_count' => $this->c_created,
            'updated_count' => $this->c_updated,
            'omitted_count' => $this->c_omitted,
            'error_count' => $this->c_errors,
            'results_details' => json_encode($this->rowsDetails), // Guardamos el JSON de todas las filas
            'imported_by' => Auth::id() ?? 1, // El admin actual
        ]);
    }
}
