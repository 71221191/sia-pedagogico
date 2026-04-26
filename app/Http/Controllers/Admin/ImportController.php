<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Imports\CoursesImport;
use App\Imports\GradesImport;
use App\Imports\LegacyPaymentsImport;
use Illuminate\Support\Facades\DB;
use App\Services\ThesisService;


class ImportController extends Controller
{
    // Función para importar Cursos
    public function importCourses(Request $request)
    {
        // 1. Validamos que realmente sea un archivo Excel
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // 2. Creamos una instancia del importador para poder leer sus resultados después
        $importador = new CoursesImport;

        // 3. Ejecutamos la importación
        Excel::import($importador, $request->file('file'));

        // 4. Sacamos el resumen que preparamos en CoursesImport.php
        $resumen = $importador->reporte;

        // 5. Preparamos el mensaje que verás en la franja verde
        $mensaje = "¡Proceso terminado! " .
                   "Creados: {$resumen['creados']}, " .
                   "Actualizados/Duplicados: {$resumen['actualizados']}, " .
                   "Omitidos: {$resumen['omitidos']}.";

        // 6. Volvemos atrás mandando el mensaje de éxito y la lista de errores
        return back()
            ->with('success', $mensaje)
            ->with('detalles_errores', $resumen['errores']);
    }

    // Tu otra función de alumnos (la dejamos igual)
    public function importStudents(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        // 1. Importación normal de alumnos a la tabla people
        Excel::import(new StudentsImport, $request->file('file'));

        // 2. DISPARADOR AUTOMÁTICO DE TESIS
        // Una vez que los alumnos están en 'people', creamos sus expedientes de grado
        app(ThesisService::class)->syncProjectsFromPeople();

        return back()->with('success', 'Alumnos y sus proyectos de tesis importados correctamente.');
    }

    public function importGrades(Request $request)
    {
        // Tiempo infinito para que PHP no corte la conexión mientras sube el archivo
        set_time_limit(0);

        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        $importId = $request->input('import_id');

        // Desactivamos el Log de consultas para no llenar la RAM
        DB::disableQueryLog();

        \Maatwebsite\Excel\Facades\Excel::queueImport(
            new \App\Imports\LegacyGradesImport($importId),
            $request->file('file')
        );

        return response()->json(['started' => true]);
    }

    public function importActiveStudents(Request $request)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        $importId = uniqid();

        // 1. Importación de alumnos activos
        \Maatwebsite\Excel\Facades\Excel::import(
            new \App\Imports\ActiveStudentsImport($importId),
            $request->file('file')
        );

        // 2. DISPARADOR AUTOMÁTICO DE TESIS
        // Nos aseguramos que si el Excel 2025 traía proyectos, se oficialicen de una vez
        app(ThesisService::class)->syncProjectsFromPeople();

        $res = \App\Imports\ActiveStudentsImport::$reporte;

        return back()
            ->with('success', "¡Importación 2025 terminada! Alumnos y proyectos sincronizados.")
            ->with('detalles_errors', $res['errores'])
            ->with('detalles_actualizados', $res['actualizados_lista']);
    }

    public function importLegacyPayments(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $importador = new LegacyPaymentsImport;

        // Ejecutamos la importación
        \Maatwebsite\Excel\Facades\Excel::import($importador, $request->file('file'));

        $res = $importador->reporte;

        return back()->with('success', "Proceso terminado. Pagos migrados: {$res['procesados']}. Omitidos: {$res['omitidos']}.")
                    ->with('detalles_errores', $res['errores']);
    }

    /**
     * Método único para procesar importaciones.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        $request->validate([
            'file'        => 'required|mimes:xlsx,xls',
            'import_type' => 'required|in:students,courses,grades,legacy_payments',
        ]);

        $file = $request->file('file');
        $type = $request->input('import_type');

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
            case 'legacy_payments':
                $import = new LegacyPaymentsImport;
                break;
            default:
                return back()->withErrors(['import_type' => 'Tipo de importación no válido.']);
        }

        \Maatwebsite\Excel\Facades\Excel::import($import, $file);

        $reporte = method_exists($import, 'reporte') ? $import->reporte : [];

        $mensaje = "Importación completada.";
        if (isset($reporte['creados'])) {
            $mensaje .= " Creados: {$reporte['creados']}.";
        }
        if (isset($reporte['actualizados'])) {
            $mensaje .= " Actualizados: {$reporte['actualizados']}.";
        }
        if (isset($reporte['omitidos'])) {
            $mensaje .= " Omitidos: {$reporte['omitidos']}.";
        }

        return back()
            ->with('success', $mensaje)
            ->with('detalles_errores', $reporte['errores'] ?? []);
    }
}
