<?php

namespace App\Services;

use App\Models\User;
use App\Models\Person;
use App\Models\Enrollment;
use App\Models\AcademicPeriod;
use App\Models\CourseSection;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Exception;

class EnrollmentService
{
    /**
     * Obtiene de manera dinámica el turno (shift_id) asignado al estudiante
     */
    public function getStudentAssignedShift(Person $person)
    {
        // Buscamos su última matrícula en el historial
        $lastEnrollment = Enrollment::where('person_id', $person->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Si ya tiene una matrícula, usamos ese turno; de lo contrario, por defecto Mañana (1)
        return $lastEnrollment ? $lastEnrollment->shift_id : 1;
    }

    public function registerEnrollment(User $user, array $sectionIds)
    {
        return DB::transaction(function () use ($user, $sectionIds) {

            // 1. Datos Base
            $person = Person::where('user_id', $user->id)->firstOrFail();
            $period = AcademicPeriod::where('status', 'open')->firstOrFail();

            // Obtenemos su turno asignado real
            $assignedShiftId = $this->getStudentAssignedShift($person);

            // Obtenemos los IDs de los cursos que el alumno ya aprobó en el pasado
            $approvedCourseIds = DB::table('enrollment_details')
                ->join('enrollments', 'enrollment_details.enrollment_id', '=', 'enrollments.id')
                ->where('enrollments.person_id', $person->id)
                ->where('enrollment_details.status', 'approved')
                ->pluck('enrollment_details.course_id')
                ->toArray();

            // 2. BUSCAR O CREAR LA CABECERA (Solo una por alumno/periodo)
            $enrollment = Enrollment::firstOrCreate(
                [
                    'person_id' => $person->id,
                    'academic_period_id' => $period->id,
                ],
                [
                    'study_plan_id' => $person->study_plan_id,
                    'cycle' => 'I', // Se calculará de forma automática en base a sus cursos
                    'enrollment_type_id' => 1,
                    'shift_id' => $assignedShiftId, // Guardamos su turno real
                    'section_label' => 'A',
                    'created_at' => now(),
                ]
            );

            // 3. PROCESAR CADA CURSO (Detalles)
            foreach ($sectionIds as $sectionId) {

                // Bloqueo de concurrencia para evitar sobreventa de vacantes
                $section = CourseSection::lockForUpdate()->with('course.prerequisites')->find($sectionId);

                if (!$section) {
                    throw new Exception("Una sección seleccionada ya no existe.");
                }

                // A. Validación de Vacantes
                if ($section->vacancy_limit <= 0) {
                    throw new Exception("El curso '{$section->course->name}' se acaba de llenar. Intenta con otra sección.");
                }

                // B. Validación de Turno (Seguridad del Backend)
                if ($section->shift_id !== $assignedShiftId) {
                    throw new Exception("No puedes matricularte en la sección '{$section->name}' porque pertenece a un turno distinto al tuyo.");
                }

                // C. Validación de Prerrequisitos
                foreach ($section->course->prerequisites as $prereq) {
                    if (!in_array($prereq->prerequisite_course_id, $approvedCourseIds)) {
                        $nombreRequerido = Course::find($prereq->prerequisite_course_id)->name ?? 'Desconocido';
                        throw new Exception("No puedes matricularte en '{$section->course->name}'. Debes aprobar antes: {$nombreRequerido}.");
                    }
                }

                // D. Evitar duplicados en la misma sección
                $exists = $enrollment->details()->where('course_section_id', $section->id)->exists();
                if ($exists) continue;

                // E. Evitar matricularse en el mismo curso en dos secciones diferentes
                $cursoDuplicado = $enrollment->details()->where('course_id', $section->course_id)->exists();
                if ($cursoDuplicado) {
                    throw new Exception("Ya estás inscrito en el curso '{$section->course->name}' en otra sección.");
                }

                // 4. CREAR EL DETALLE
                $enrollment->details()->create([
                    'course_id' => $section->course_id,
                    'course_section_id' => $section->id,
                    'status' => 'enrolled',
                    'attempt_number' => 1,
                    'final_score_numeric' => null,
                    'is_legacy' => false
                ]);

                // 5. RESTAR VACANTE
                $section->decrement('vacancy_limit');
            }

            return $enrollment;
        });
    }

    public function getAvailableSectionsWithStatus(Person $person, AcademicPeriod $period)
    {
        // Determinamos el plan de estudios del alumno
        $planId = $person->study_plan_id ?? 0;

        // Obtenemos su turno asignado
        $assignedShiftId = $this->getStudentAssignedShift($person);

        // 1. Cursos aprobados en el pasado
        $approvedCourseIds = DB::table('enrollment_details')
            ->join('enrollments', 'enrollment_details.enrollment_id', '=', 'enrollments.id')
            ->where('enrollments.person_id', $person->id)
            ->where('enrollment_details.status', 'approved')
            ->pluck('enrollment_details.course_id')
            ->toArray();

        // 2. Cursos ya matriculados en este periodo actual
        $alreadyEnrolledCourseIds = DB::table('enrollment_details')
            ->join('enrollments', 'enrollment_details.enrollment_id', '=', 'enrollments.id')
            ->where('enrollments.person_id', $person->id)
            ->where('enrollments.academic_period_id', $period->id)
            ->pluck('enrollment_details.course_id')
            ->toArray();

        // 3. Traemos las secciones filtrando estrictamente por el turno asignado al alumno
        $sections = CourseSection::with(['course.prerequisites', 'teacher'])
            ->where('academic_period_id', $period->id)
            ->where('shift_id', $assignedShiftId) // FILTRO ESTRICTO DE TURNO
            ->whereHas('course', function($q) use ($planId) {
                $q->where('study_plan_id', $planId);
            })
            ->get();

        return $sections->map(function ($section) use ($approvedCourseIds, $alreadyEnrolledCourseIds) {
            $course = $section->course;
            $status = 'available';
            $lockReason = null;

            // REGLA A: ¿Ya lo aprobó en el pasado?
            if (in_array($course->id, $approvedCourseIds)) {
                $status = 'passed';
            }
            // REGLA B: ¿Ya está matriculado en este periodo actual?
            elseif (in_array($course->id, $alreadyEnrolledCourseIds)) {
                $status = 'enrolled';
                $lockReason = "Ya estás matriculado en este curso.";
            }
            else {
                // REGLA C: ¿Cumple con los prerrequisitos?
                foreach ($course->prerequisites as $prereq) {
                    if (!in_array($prereq->prerequisite_course_id, $approvedCourseIds)) {
                        $status = 'locked';
                        $missingCourse = Course::find($prereq->prerequisite_course_id);
                        $lockReason = "Falta aprobar: " . ($missingCourse ? $missingCourse->name : 'Prerrequisito');
                        break;
                    }
                }
            }

            // REGLA D: Cálculo de vacantes reales en base a detalles creados
            $inscritos = DB::table('enrollment_details')
                ->where('course_section_id', $section->id)
                ->count();
            $disponibles = $section->vacancy_limit - $inscritos;

            if ($status === 'available' && $disponibles <= 0) {
                $status = 'no_vacancies';
                $lockReason = "Ya no quedan vacantes";
            }

            return [
                'id' => $section->id,
                'cycle' => $course->cycle,
                'course_code' => $course->code,
                'course_name' => $course->name,
                'credits' => $course->credits,
                'section_name' => $section->name,
                'teacher_name' => $section->teacher ? $section->teacher->full_name : 'Por asignar',
                'status' => $status, // 'available', 'locked', 'passed', 'no_vacancies', 'enrolled'
                'lock_reason' => $lockReason,
                'vacancy_limit' => $section->vacancy_limit,
                'remaining_vacancies' => $disponibles,
            ];
        });
    }

    public function checkAdministrativeRequirements(Person $person, AcademicPeriod $period)
    {
        // 1. Validar Ficha Socioeconómica aprobada
        $ficha = DB::table('socioeconomic_files')
            ->where('person_id', $person->id)
            ->where('academic_period_id', $period->id)
            ->first();
        $fichaOk = ($ficha && $ficha->is_validated);

        // 2. Validar que no tenga deudas de libros en biblioteca
        $lastEnrollment = DB::table('enrollments')
            ->where('person_id', $person->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $bibliotecaOk = !($lastEnrollment && $lastEnrollment->library_debt);

        // 3. Validar pago de matrícula o beca activa
        $tieneBeca = !is_null($person->scholarship_type_id) && $person->scholarship_type_id > 1;
        $pagoOk = $tieneBeca || DB::table('payments')
            ->where('person_id', $person->id)
            ->where('concept', 'Matrícula')
            ->where('status', 'approved')
            ->exists();

        return [
            'can_enroll' => ($fichaOk && $bibliotecaOk && $pagoOk),
            'details' => [
                'ficha' => $fichaOk,
                'biblioteca' => $bibliotecaOk,
                'pago' => $pagoOk,
            ]
        ];
    }
}
