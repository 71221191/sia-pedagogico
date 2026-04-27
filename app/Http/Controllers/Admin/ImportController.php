<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Imports\CoursesImport;
use App\Imports\GradesImport;
use App\Imports\LegacyPaymentsImport;
use App\Imports\ActiveStudentsImport;

class ImportController extends Controller
{
    /**
     * Método único para procesar cualquier importación masiva.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        // Validar archivo (Excel o CSV) y tipo de importación
        $request->validate([
            'file'        => 'required|mimes:xlsx,xls,csv|max:10240', // 10 MB máximo
            'import_type' => 'required|in:students,courses,grades,payments,active_students',
        ]);

        $file = $request->file('file');
        $type = $request->input('import_type');

        // Mapeo inteligente: seleccionar la clase de importación según el tipo
        switch ($type) {
            case 'students':
                $import = new StudentsImport;
                break;
            case 'courses':
                $import = new CoursesImport;
                break;
            case 'grades':
                $import = new GradesImport;
                break;
            case 'payments':
                $import = new LegacyPaymentsImport;
                break;
            case 'active_students':
                $import = new ActiveStudentsImport;
                break;
            default:
                return back()->withErrors(['import_type' => 'Tipo de importación no válido.']);
        }

        // Ejecutar la importación
        Excel::import($import, $file);

        // Obtener el reporte generado por la clase de importación
        $reporte = method_exists($import, 'reporte') ? $import->reporte : [];

        // Construir mensaje de respuesta
        $mensaje = "Importación completada.";
        if (isset($reporte['nuevos'])) {
            $mensaje .= " Nuevos: {$reporte['nuevos']}.";
        }
        if (isset($reporte['creados'])) {
            $mensaje .= " Creados: {$reporte['creados']}.";
        }
        if (isset($reporte['actualizados'])) {
            $mensaje .= " Actualizados: {$reporte['actualizados']}.";
        }
        if (isset($reporte['omitidos'])) {
            $mensaje .= " Omitidos: {$reporte['omitidos']}.";
        }
        if (isset($reporte['procesados'])) {
            $mensaje .= " Procesados: {$reporte['procesados']}.";
        }

        return back()
            ->with('success', $mensaje)
            ->with('detalles_errores', $reporte['errores'] ?? []);
    }

    /**
     * Método específico para importar alumnos activos 2026-I (usado por ruta dedicada)
     */
    public function importActiveStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new ActiveStudentsImport;
        Excel::import($import, $request->file('file'));

        $reporte = $import->reporte;

        $mensaje = "Importación de alumnos activos completada. Nuevos: {$reporte['nuevos']}, Actualizados: {$reporte['actualizados']}.";

        return back()
            ->with('success', $mensaje)
            ->with('detalles_errores', $reporte['errores'] ?? []);
    }
}
