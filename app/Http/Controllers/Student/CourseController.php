<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\CourseSection;
use App\Models\EnrollmentDetail;
use App\Models\GradeScale;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Listado de cursos matriculados en el semestre activo
     */
    public function index()
    {
        $user = Auth::user();
        $person = $user->person;
        $openPeriod = AcademicPeriod::where('status', 'open')->first();

        $courses = [];

        if ($openPeriod && $person) {
            $enrollment = Enrollment::where('person_id', $person->id)
                ->where('academic_period_id', $openPeriod->id)
                ->with(['details.course', 'details.courseSection.teacher'])
                ->first();

            if ($enrollment) {
                $courses = $enrollment->details->map(function($detail) {
                    return [
                        'section_id'   => $detail->course_section_id,
                        'course_name'  => $detail->course->name,
                        'course_code'  => $detail->course->code,
                        'section_name' => $detail->courseSection->name ?? 'A',
                        'teacher_name' => $detail->courseSection->teacher->full_name ?? 'Sin asignar',
                        'cycle'        => $detail->course->cycle,
                    ];
                });
            }
        }

        return Inertia::render('Student/Courses/Index', [
            'courses' => $courses,
            'periodName' => $openPeriod->name ?? 'Sin periodo activo'
        ]);
    }

    /**
     * Detalle, horario, sílabo y calificaciones cualitativas del curso seleccionado
     */
    public function show($sectionId)
    {
        $user = Auth::user();
        $person = $user->person;

        // 1. Cargar la sección del curso con sus relaciones
        $section = CourseSection::with(['course.competencies', 'course.studyPlan.studyProgram', 'teacher', 'academicPeriod'])
            ->findOrFail($sectionId);

        // 2. Obtener el detalle de la matrícula del estudiante en esta sección
        $detail = EnrollmentDetail::where('course_section_id', $section->id)
            ->whereHas('enrollment', function($q) use ($person) {
                $q->where('person_id', $person->id);
            })
            ->with(['grades.gradeScale'])
            ->firstOrFail();

        // 3. Mapear las competencias con la escala lograda por el estudiante
        $competencies = $section->course->competencies->map(function($comp) use ($detail) {
            $grade = $detail->grades->firstWhere('competency_id', $comp->id);
            return [
                'code' => $comp->code,
                'description' => $comp->description,
                'scale_name' => $grade->gradeScale->name ?? 'Pendiente',
            ];
        });

        // 4. Buscar el equivalente Cualitativo (Logrado, Destacado, etc.) para el PROMEDIO FINAL
        $gradeScales = GradeScale::all();
        $finalScaleName = 'Pendiente';

        if (!is_null($detail->final_score_numeric)) {
            $closest = null;
            // Algoritmo matemático para encontrar la escala con el equivalente numérico más cercano
            foreach ($gradeScales as $scale) {
                if ($closest === null || abs($scale->numeric_equivalent - $detail->final_score_numeric) < abs($closest->numeric_equivalent - $detail->final_score_numeric)) {
                    $closest = $scale;
                }
            }
            $finalScaleName = $closest ? $closest->name : 'Pendiente';
        }

        // 5. Cargar el Horario exclusivo de este curso
        $daysMap = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes'];
        $schedule = Schedule::with(['timeSlot', 'classroom'])
            ->where('course_section_id', $section->id)
            ->get()
            ->map(function($s) use ($daysMap) {
                return [
                    'day_name' => $daysMap[$s->day_of_week] ?? 'N/A',
                    'start'    => date('h:i a', strtotime($s->timeSlot->start_time)),
                    'end'      => date('h:i a', strtotime($s->timeSlot->end_time)),
                    'classroom'=> $s->classroom->name ?? 'S.A.',
                ];
            });

        return Inertia::render('Student/Courses/Show', [
            'section' => $section,
            'competencies' => $competencies,
            'finalScaleName' => $finalScaleName,
            'schedule' => $schedule,
            'status' => $detail->status // enrolled, approved, failed
        ]);
    }
}
