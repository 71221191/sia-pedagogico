<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Models\Person;
use App\Helpers\NumberHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Traits\StandardizesAcademicData;


class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function cuadroEstadistico(Request $request)
    {
        // 1. Damos más memoria y tiempo al servidor para procesos pesados
        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        // 2. Obtener el periodo seleccionado
        $periodId = $request->query('academic_period_id', 1);
        $periodo = \App\Models\AcademicPeriod::find($periodId);

        if (!$periodo) {
            return back()->with('error', 'Periodo no encontrado.');
        }

        // 3. Consulta Maestra: Obtenemos los conteos agrupados
        $dataRaw = \App\Models\Enrollment::where('academic_period_id', $periodId)
            ->join('people', 'enrollments.person_id', '=', 'people.id')
            ->join('study_plans', 'enrollments.study_plan_id', '=', 'study_plans.id')
            ->join('study_programs', 'study_plans.study_program_id', '=', 'study_programs.id')
            ->select(
                'study_programs.name as program_name',
                'enrollments.cycle',
                'people.gender',
                \Illuminate\Support\Facades\DB::raw('count(*) as total')
            )
            ->groupBy('program_name', 'enrollments.cycle', 'people.gender')
            ->get();

        // 4. Identificamos solo los programas que tienen alumnos matriculados
        $allProgramNames = $dataRaw->pluck('program_name')->unique()->sort()->values();

        // LA MAGIA: Dividimos los programas en grupos de 4 para que quepan bien en la hoja
        $programChunks = $allProgramNames->chunk(4);

        // 5. Asignamos colores pastel de forma dinámica a cada carrera
        $palette = $this->getPastelPalette();
        $programColors = [];
        foreach ($allProgramNames as $index => $name) {
            $programColors[$name] = $palette[$index % count($palette)];
        }

        // 6. Inicializamos la Matriz de datos (10 ciclos x Todas las carreras)
        $cycles = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $matrix = [];
        $totalsByProgram = [];
        $grandTotalM = 0;
        $grandTotalF = 0;

        foreach ($cycles as $cycle) {
            $matrix[$cycle] = ['programs' => [], 'row_total' => 0];
            foreach ($allProgramNames as $prog) {
                $matrix[$cycle]['programs'][$prog] = ['M' => 0, 'F' => 0, 'total' => 0];
                if (!isset($totalsByProgram[$prog])) {
                    $totalsByProgram[$prog] = ['M' => 0, 'F' => 0, 'total' => 0];
                }
            }
        }

        // 7. Llenamos la matriz con los resultados de la base de datos
        foreach ($dataRaw as $row) {
            $c = (int)$row->cycle;
            $p = $row->program_name;
            $g = ($row->gender == 'M') ? 'M' : 'F';
            $t = $row->total;

            if (isset($matrix[$c])) {
                $matrix[$c]['programs'][$p][$g] = $t;
                $matrix[$c]['programs'][$p]['total'] += $t;
                $matrix[$c]['row_total'] += $t;

                $totalsByProgram[$p][$g] += $t;
                $totalsByProgram[$p]['total'] += $t;

                if ($g == 'M') $grandTotalM += $t;
                else $grandTotalF += $t;
            }
        }

        // Datos extra para el resumen A1
        $totalDisability = \App\Models\Enrollment::where('academic_period_id', $periodId)
            ->join('people', 'enrollments.person_id', '=', 'people.id')
            ->where('people.has_disability', true)->count();

        $totalScholarships = \App\Models\Enrollment::where('academic_period_id', $periodId)
            ->join('people', 'enrollments.person_id', '=', 'people.id')
            ->whereNotNull('people.scholarship_modality')
            ->where('people.scholarship_modality', '!=', 'NINGUNA')->count();

        $viewData = [
            'periodo'         => $periodo,
            'programChunks'   => $programChunks,
            'programColors'   => $programColors,
            'cycles'          => $cycles,
            'matrix'          => $matrix,
            'totalsByProgram' => $totalsByProgram,
            'grandTotalM'     => $grandTotalM,
            'grandTotalF'     => $grandTotalF,
            'grandTotal'      => $grandTotalM + $grandTotalF,
            'totalDisability' => $totalDisability,   // <--- NUEVO
            'totalScholarships' => $totalScholarships, // <--- NUEVO
            'numberHelper'    => new \App\Helpers\NumberHelper(),
            // Logos SVG en Base64 (usamos la misma lógica que en la nómina)
            'logoInsti'       => 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path('img/logo-instituto.svg'))),
            'logoMinedu'      => 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path('img/logo-minedu.svg'))),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.cuadro_estadistico', $viewData);
        return $pdf->setPaper('a4', 'landscape')->stream();
    }


    public function downloadCertificate($personId)
    {
        $person = Person::findOrFail($personId);
        $history = $this->reportService->getConsolidatedHistory($personId);

        // FUNCIÓN PARA OPTIMIZAR IMÁGENES
        $logoMinedu = $this->convertImageToBase64(public_path('img/logo-minedu.png'));
        $logoInsti = $this->convertImageToBase64(public_path('img/logo-instituto.png'));

        $pdf = Pdf::loadView('reports.certificate', [
            'person' => $person,
            'history' => $history,
            'numberHelper' => new NumberHelper(),
            'logoMinedu' => $logoMinedu, // Pasamos la imagen ya convertida
            'logoInsti' => $logoInsti
        ]);

        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream("Certificado_{$person->dni}.pdf");
    }

    // Helper interno para la conversión
    private function convertImageToBase64($path)
    {
        if (!file_exists($path)) return null;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    use StandardizesAcademicData;

    public function nominaMatricula(Request $request, \App\Models\CourseSection $courseSection)
    {
        $courseSection->load(['course.studyPlan', 'academicPeriod']);

        // --- SOLUCIÓN: Normalizamos el ciclo antes de buscar ---
        // Esto convierte "I" o "Ciclo I" en 1
        $cicloBusqueda = $this->traducirCicloANumero($courseSection->course->cycle);

        // También limpiamos la sección por si tiene espacios invisibles
        $seccionBusqueda = trim($courseSection->name);

        // 1. Obtener alumnos con la búsqueda "blindada"
        $enrollments = \App\Models\Enrollment::with('person')
            ->where('academic_period_id', $courseSection->academic_period_id)
            ->where('study_plan_id', $courseSection->course->study_plan_id)
            ->where('cycle', $cicloBusqueda)
            ->where('section_label', $seccionBusqueda)
            ->where('shift_id', $courseSection->shift_id)
            ->get()
            ->sortBy(function($e) {
                // 1. Limpiamos espacios en blanco al inicio o final que pudo traer el Excel
                $paterno = trim($e->person->last_name_p);
                $materno = trim($e->person->last_name_m);
                $nombres = trim($e->person->names);

                // 2. Devolvemos la cadena completa para ordenar (Paterno + Materno + Nombres)
                return "{$paterno} {$materno} {$nombres}";
            })
            ->values(); // 3. Muy importante: Reseteamos los índices para que el bucle empiece de 0


        // 2. Datos dinámicos del Modal
        $params = [
            'rdr' => $request->query('rdr'),
            'rdr_encargatura' => $request->query('rdr_encargatura'),
            'fecha_cierre' => \Carbon\Carbon::parse($request->query('fecha_cierre'))->translatedFormat('d \d\e F \d\e\l Y'),
            'dia' => \Carbon\Carbon::parse($request->query('fecha_cierre'))->format('d'),
            'mes' => \Carbon\Carbon::parse($request->query('fecha_cierre'))->translatedFormat('F'),
            'anio' => \Carbon\Carbon::parse($request->query('fecha_cierre'))->format('Y'),
        ];

        // 1. Mejoramos la colección de alumnos para incluir edad y tipo de pago
        $enrollments = $enrollments->map(function($e) {
            // Cálculo de edad dinámica (Año actual - Año nacimiento)
            $e->edad = \Carbon\Carbon::parse($e->person->birth_date)->age;

            // Lógica de Pago: Si tiene beca es Gratuito (G), sino Pagante (P)
            // Buscamos si tiene registro de beca en la tabla people
            $e->tipo_pago = ($e->person->scholarship_modality && $e->person->scholarship_modality != 'NINGUNA') ? 'G' : 'P';

            return $e;
        });

        // 3. Estadísticas del cuadro resumen
        $stats = [
            'hombres'   => $enrollments->filter(fn($e) => $e->person->gender == 'M')->count(),
            'mujeres'   => $enrollments->filter(fn($e) => $e->person->gender == 'F')->count(),
            'gratuitos' => $enrollments->filter(fn($e) => $e->tipo_pago == 'G')->count(),
            'pagantes'  => $enrollments->filter(fn($e) => $e->tipo_pago == 'P')->count(),
            'total'     => $enrollments->count(),
        ];

        // 4. Preparar Logos (Base64 para que carguen rápido en PDF)
        /* $logoMinedu = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo-minedu.png')));
        $logoInsti = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo-instituto.png'))); */
        // LEEMOS EL ARCHIVO SVG FÍSICO
        $pathInsti = public_path('img/logo-instituto.svg');
        $pathMinedu = public_path('img/logo-minedu.svg');

        // Verificamos si los archivos existen antes de intentar leerlos para que no explote
        $logoInsti = "";
        $logoMinedu = "";

        if (file_exists($pathInsti)) {
            $logoInsti = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($pathInsti));
        }

        if (file_exists($pathMinedu)) {
            $logoMinedu = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($pathMinedu));
        }

        $ds_creacion = "D.S. 008-1983-ED";
        $carrera = $courseSection->course->studyPlan->studyProgram->name . " (" . $courseSection->course->studyPlan->resolution_code . ")";

        $pdf = Pdf::loadView('reports.nomina', [
            'section'      => $courseSection,
            'enrollments'  => $enrollments,
            'params'       => $params,
            'stats'        => $stats,
            'logoInsti'    => $logoInsti,
            'logoMinedu'   => $logoMinedu,
            'ds_creacion'  => $ds_creacion, // Necesaria para tu HTML
            'carrera'      => $carrera,     // Necesaria para tu HTML
            'numberHelper' => new \App\Helpers\NumberHelper()
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream("Nomina_{$courseSection->name}.pdf");
    }

    private function getPastelPalette()
    {
        return [
            '#E0F2FE', '#DCFCE7', '#FEF9C3', '#FEE2E2', '#F3E8FF', '#FFEDD5', '#E0E7FF', '#FCE7F3', '#CFFAFE', '#F5F5F4',
            '#D1FAE5', '#FFFAE5', '#ECFCCB', '#DFF6FF', '#FAE8FF', '#F1F5F9', '#FFF1F2', '#F0FDFA', '#FFF7ED', '#EEF2FF',
            '#E2E8F0', '#F0F9FF', '#F5F3FF', '#FEFCE8', '#ECFDF5', '#FFFBF0', '#F8FAFC', '#FDF2F8', '#F5FEFD', '#F9FAFB'
        ];
    }
}
