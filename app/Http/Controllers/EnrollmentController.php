<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AcademicPeriod;
use App\Models\Person;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\Course;
use App\Services\EnrollmentService;
use App\Http\Requests\StoreEnrollmentRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class EnrollmentController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Muestra la interfaz para que el alumno se matricule o consulte su constancia finalizada.
     */
    public function create()
    {
        $user = Auth::user();
        $person = Person::where('user_id', $user->id)->firstOrFail();
        $period = AcademicPeriod::where('status', 'open')->first();

        if (!$period) {
            return redirect()->route('dashboard')->with('error', 'No hay un proceso de matrícula activo.');
        }

        // 1. VERIFICAR SI EL ALUMNO YA TIENE UNA MATRÍCULA REGISTRADA EN ESTE PERIODO ACTIVO
        $enrollment = Enrollment::where('person_id', $person->id)
            ->where('academic_period_id', $period->id)
            ->with(['details.course.studyPlan.studyProgram', 'details.courseSection.teacher', 'studyPlan'])
            ->first();

        if ($enrollment) {
            // CASO A: YA ESTÁ MATRICULADO -> Redirigimos a la vista de Constancia (Show)
            $enrolledCourses = $enrollment->details->map(function ($detail) {
                return [
                    'code' => $detail->course->code,
                    'name' => $detail->course->name,
                    'credits' => $detail->course->credits,
                    'section' => $detail->courseSection->name ?? 'A',
                    'teacher' => $detail->courseSection->teacher->full_name ?? 'Sin asignar',
                    'enrolled_at' => $detail->created_at ? $detail->created_at->format('d/m/Y h:i A') : '--',
                    'observation' => $detail->attempt_number > 1 ? 'REPETIDO' : 'NUEVO',
                    'plan' => $detail->course->studyPlan->name ?? '2019',
                ];
            });

            // Extraer el nombre del turno asignado
            $shiftName = ($enrollment->shift_id == 1) ? 'MAÑANA' : 'TARDE';
            $admissionYear = $person->created_at ? $person->created_at->format('Y') : date('Y');

            return Inertia::render('Enrollment/Show', [
                'enrollment' => [
                    'id' => $enrollment->id,
                    'admission_year' => $admissionYear,
                    'plan_name' => $enrollment->studyPlan->name ?? '2019',
                    'cycle' => $enrollment->cycle,
                    'courses_count' => $enrolledCourses->count(),
                    'date' => $enrollment->created_at ? $enrollment->created_at->format('d/m/Y') : '--',
                    'total_credits' => $enrolledCourses->sum('credits'),
                    'shift_name' => $shiftName, // Turno enviado al frontend
                ],
                'courses' => $enrolledCourses,
                'periodName' => $period->name,
                'studentName' => "{$person->last_name_p} {$person->last_name_m}, {$person->names}"
            ]);
        }

        // CASO B: NO TIENE MATRÍCULA -> Cargamos el Formulario de Matrícula Activa
        $requirements = $this->enrollmentService->checkAdministrativeRequirements($person, $period);
        $availableSections = $this->enrollmentService->getAvailableSectionsWithStatus($person, $period);

        // Obtenemos el turno asignado del estudiante
        $assignedShiftId = $this->enrollmentService->getStudentAssignedShift($person);
        $shiftName = ($assignedShiftId == 1) ? 'MAÑANA' : 'TARDE';

        return Inertia::render('Enrollment/Create', [
            'requirements' => $requirements,
            'availableSections' => $availableSections,
            'studentStudyPlanId' => $person->study_plan_id ?? 0,
            'currentPeriod' => $period->name,
            'currentPeriodId' => $period->id,
            'student' => $person->names,
            'shiftName' => $shiftName, // Pasamos el turno asignado para la cabecera
            'sectionsByCycle' => $availableSections->groupBy('cycle'),
        ]);
    }

    /**
     * Procesa la matrícula de los cursos seleccionados por el alumno.
     */
    public function store(StoreEnrollmentRequest $request)
    {
        try {
            $this->enrollmentService->registerEnrollment(
                Auth::user(),
                $request->validated()['sections']
            );

            return redirect()->route('student.enrollment.create')
                ->with('success', '¡Matrícula procesada exitosamente!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Generar la Ficha de Matrícula Oficial en PDF (Fiel al formato impreso)
     */
    public function downloadPdf($enrollmentId)
    {
        $enrollment = Enrollment::with([
            'person.user',
            'academicPeriod',
            'studyPlan.studyProgram',
            'details.course.studyPlan',
            'details.courseSection.teacher'
        ])->findOrFail($enrollmentId);

        // CORRECCIÓN: Colocamos el tipado para eliminar el error rojo de VS Code
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('estudiante') && $enrollment->person_id !== $user->person->id) {
            abort(403, 'No tienes permiso para ver esta constancia.');
        }

        $person = $enrollment->person;

        $enrolledCourses = $enrollment->details->map(function ($detail) {
            return [
                'code' => $detail->course->code,
                'name' => $detail->course->name,
                'hours' => $detail->course->hours_total ?? 0, // Extraemos las horas del curso
                'credits' => $detail->course->credits,
                'section' => $detail->courseSection->name ?? 'A',
                'teacher' => $detail->courseSection->teacher->full_name ?? 'Sin asignar',
                'observation' => $detail->attempt_number > 1 ? 'REPETIDO' : 'NUEVO',
                'plan' => $detail->course->studyPlan->name ?? '2019',
            ];
        });

        // Usamos public_path directamente en las imágenes para optimizar velocidad al 100% en DomPDF
        $logoMinedu = public_path('img/logo-minedu.png');
        $logoInsti = public_path('img/logo-instituto.png');

        $pdf = Pdf::loadView('reports.enrollment_certificate', [
            'person' => $person,
            'enrollment' => $enrollment,
            'courses' => $enrolledCourses,
            'totalHours' => $enrolledCourses->sum('hours'), // Sumatoria de horas
            'totalCredits' => $enrolledCourses->sum('credits'), // Sumatoria de créditos
            'logoMinedu' => $logoMinedu,
            'logoInsti' => $logoInsti,
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream("Ficha_Matricula_{$person->dni}.pdf");
    }
}
