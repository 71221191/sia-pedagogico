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
use App\Imports\TeachersImport;

class ImportController extends Controller
{
    public function process(Request $request)
    {
        // 1. IMPORTANTE: Agregamos 'teachers' a la lista permitida
        $request->validate([
            'file'        => 'required|mimes:xlsx,xls,csv|max:10240',
            'import_type' => 'required|in:students,courses,grades,payments,active_students,teachers',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $type = $request->input('import_type');
        $importId = uniqid();

        switch ($type) {
            case 'teachers':
                // Ahora sí le pasamos el nombre del archivo al docente
                $import = new TeachersImport($originalName);
                break;

            case 'students':
            // Le pasamos el ID y el NOMBRE DEL ARCHIVO
                $import = new ActiveStudentsImport($importId, $originalName);
                break;

            case 'active_students':
                $import = new ActiveStudentsImport($importId, $originalName);
                break;

            case 'courses':
                $import = new CoursesImport;
                break;

            case 'grades':
                $import = new GradesImport;
                break;

            case 'payments':
                $import = new LegacyPaymentsImport($originalName);
                break;

            default:
                return back()->withErrors(['import_type' => 'Tipo de importación no válido.']);
        }

        // 2. Ejecutar la importación
        Excel::import($import, $file);

        $res = $import->getReporte();

        // 3. Obtener reporte (asegurándonos de que no de error si no existe alguna llave)
        $reporte = $import->getReporte();
        $creados = $reporte['creados'] ?? 0;
        $actualizados = $reporte['actualizados'] ?? 0;
        $omitidos = $reporte['omitidos'] ?? 0;

        $mensaje = "Importación completada. Creados: {$creados}, Actualizados: {$actualizados}, Omitidos: {$omitidos}.";

        // 4. Manejo de errores detallados
        if (isset($reporte['errores']) && count($reporte['errores']) > 0) {
            return back()->with('warning', $mensaje)->with('detalles_errores', $reporte['errores']);
        }

        return back()->with('success', $mensaje);
    }

    public function importActiveStudents(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:10240']);

        $importId = uniqid();
        $import = new ActiveStudentsImport($importId);

        Excel::import($import, $request->file('file'));

        // AQUÍ ESTABA EL ERROR (Línea 70 exacta)
        $reporte = $import->reporte;

        $mensaje = "Importación 2026-I finalizada. Creados: {$reporte['creados']}, Actualizados: {$reporte['actualizados']}.";

        return back()
            ->with('success', $mensaje)
            ->with('detalles_errores', $reporte['errores'] ?? []);
    }
}
