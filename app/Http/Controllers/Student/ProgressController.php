<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\AcademicPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Helpers\NumberHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\Enrollment;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $person = $user->person;
        $openPeriod = AcademicPeriod::where('status', 'open')->first();

        // --- 1. LÓGICA DE HISTORIAL COMPLETO (KÁRDEX) ---
        $academicHistory = $person->enrollments()
            ->with(['academicPeriod', 'details.course'])
            ->get()
            ->map(function ($enrollment) {
                return [
                    'period_name' => $enrollment->academicPeriod->name,
                    'cycle' => $enrollment->cycle,
                    'courses' => $enrollment->details->map(function ($detail) {
                        return [
                            'code' => $detail->course->code,
                            'name' => $detail->course->name,
                            'credits' => $detail->course->credits,
                            'grade' => $detail->final_score_numeric,
                            'status' => $detail->status, // approved, failed, enrolled
                        ];
                    })
                ];
            })->sortByDesc('period_name')->values();

        // 2. OBTENER EL PROMEDIO Y PUESTO OFICIAL (Desde la tabla de rankings)
        $rankingOficial = DB::table('academic_rankings')
            ->where('person_id', $person->id)
            ->join('academic_periods', 'academic_rankings.academic_period_id', '=', 'academic_periods.id')
            ->orderBy('academic_periods.start_date', 'desc')
            ->select('academic_rankings.*')
            ->first();

        // 3. Recuperamos el total de créditos aprobados de toda su historia
        $totalCredits = DB::table('enrollment_details')
            ->join('courses', 'enrollment_details.course_id', '=', 'courses.id')
            ->join('enrollments', 'enrollment_details.enrollment_id', '=', 'enrollments.id')
            ->where('enrollments.person_id', $person->id)
            ->where('enrollment_details.status', 'approved')
            ->sum('courses.credits');

        return Inertia::render('Student/Progress', [
            'academicHistory' => $academicHistory,
            'ppa' => $rankingOficial ? (float)$rankingOficial->weighted_average : 0,
            'totalCredits' => (int)$totalCredits,
            'studentName' => $person->names,
            'periodName' => $openPeriod->name ?? 'Sin periodo activo'
        ]);
    }

    private function convertImageToBase64($imagePath)
    {
        if (!file_exists($imagePath)) {
            return '';
        }
        $imageData = file_get_contents($imagePath);
        $base64 = base64_encode($imageData);
        $mime = mime_content_type($imagePath);
        return 'data:' . $mime . ';base64,' . $base64;
    }

    public function downloadPdf()
    {
        $person = Auth::user()->person;

        // 1. Cargamos el historial completo (igual que en el index)
        $history = $person->enrollments()
            ->with(['academicPeriod', 'details.course'])
            ->get()
            ->sortBy('academicPeriod.start_date'); // De más antiguo a más nuevo para el PDF

        // 2. Cargamos el Ranking más reciente
        $ranking = DB::table('academic_rankings')
            ->where('person_id', $person->id)
            ->join('academic_periods', 'academic_rankings.academic_period_id', '=', 'academic_periods.id')
            ->orderBy('academic_periods.start_date', 'desc')
            ->select('academic_rankings.*')
            ->first();

        // 3. Calculamos créditos totales
        $totalCredits = DB::table('enrollment_details')
            ->join('courses', 'enrollment_details.course_id', '=', 'courses.id')
            ->join('enrollments', 'enrollment_details.enrollment_id', '=', 'enrollments.id')
            ->where('enrollments.person_id', $person->id)
            ->where('enrollment_details.status', 'approved')
            ->sum('courses.credits');

        // 4. Logos y Vista
        $logoMinedu = $this->convertImageToBase64(public_path('img/logo-minedu.png'));
        $logoInsti = $this->convertImageToBase64(public_path('img/logo-instituto.png'));

        $pdf = Pdf::loadView('reports.academic_record', [
            'person' => $person,
            'history' => $history,
            'ppa' => $ranking ? $ranking->weighted_average : 0,
            'totalCredits' => $totalCredits,
            'logoMinedu' => $logoMinedu,
            'logoInsti' => $logoInsti,
            'numberHelper' => new NumberHelper()
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream("Record_Notas_{$person->dni}.pdf");
    }

    public function mySchedule()
    {
        $user = Auth::user();
        $person = $user->person;
        $period = AcademicPeriod::where('status', 'open')->first();

        if (!$period) return back()->with('error', 'No hay un periodo activo.');

        // 1. Buscamos todas las clases de las secciones donde el alumno está matriculado
        $schedules = \App\Models\Schedule::with(['course', 'classroom', 'timeSlot', 'teacher'])
        ->where('academic_period_id', $period->id)
        ->whereIn('course_section_id', function($query) use ($person, $period) {
            $query->select('course_section_id')
                ->from('enrollment_details')
                ->join('enrollments', 'enrollment_details.enrollment_id', '=', 'enrollments.id')
                ->where('enrollments.person_id', $person->id)
                ->where('enrollments.academic_period_id', $period->id);
        })
        ->get();

        // 1. Buscamos el turno del alumno basado en su última matrícula
        $currentEnrollment = \App\Models\Enrollment::where('person_id', $person->id)
            ->where('academic_period_id', $period->id)
            ->first();

        $shiftId = $currentEnrollment ? $currentEnrollment->shift_id : 1;
        $turnoNombre = ($shiftId == 1) ? 'mañana' : 'tarde';

        return Inertia::render('Student/MySchedule', [
            'schedules' => $schedules,
            'shiftId'   => $shiftId, // Enviamos el ID del turno
            'timeSlots' => \App\Models\TimeSlot::where('shift', $turnoNombre)
                            ->orderBy('start_time', 'asc') // Orden cronológico real
                            ->get(),
            'days' => [
                ['id' => 1, 'name' => 'Lunes'], ['id' => 2, 'name' => 'Martes'],
                ['id' => 3, 'name' => 'Miércoles'], ['id' => 4, 'name' => 'Jueves'],
                ['id' => 5, 'name' => 'Viernes'],
            ]
        ]);
    }

    public function downloadScheduleExcel()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $person = $user->person;
        $period = AcademicPeriod::where('status', 'open')->first();

        if (!$period) return back()->with('error', 'No hay periodo activo.');

        $enrollment = Enrollment::where('person_id', $person->id)
            ->where('academic_period_id', $period->id)
            ->first();
        $shiftName = ($enrollment && $enrollment->shift_id == 2) ? 'tarde' : 'mañana';

        // 1. Reutilizamos tu lógica de mapeo
        $schedules = Schedule::with(['course', 'classroom', 'timeSlot', 'teacher'])
            ->where('academic_period_id', $period->id)
            ->whereIn('course_section_id', function($query) use ($person, $period) {
                $query->select('course_section_id')
                    ->from('enrollment_details')
                    ->join('enrollments', 'enrollment_details.enrollment_id', '=', 'enrollments.id')
                    ->where('enrollments.person_id', $person->id)
                    ->where('enrollments.academic_period_id', $period->id);
            })->get();

        $mapaHorario = [];
        foreach ($schedules as $s) {
            $key = $s->day_of_week . '-' . $s->time_slot_id;
            $mapaHorario[$key] = [
                'curso' => $s->course->name,
                'profe' => $s->teacher->full_name,
                'aula'  => $s->classroom->name ?? 'S.A.'
            ];
        }

        $data = [
            'person' => $person,
            'timeSlots' => TimeSlot::where('shift', $shiftName)->orderBy('start_time')->get(),
            'days' => [['id' => 1, 'name' => 'LUNES'], ['id' => 2, 'name' => 'MARTES'], ['id' => 3, 'name' => 'MIÉRCOLES'], ['id' => 4, 'name' => 'JUEVES'], ['id' => 5, 'name' => 'VIERNES']],
            'mapaHorario' => $mapaHorario,
            'periodName' => $period->name
        ];

        // 2. Exportamos usando la clase nueva
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\StudentScheduleExport($data),
            "Horario_{$person->dni}.xlsx"
        );
    }
}
