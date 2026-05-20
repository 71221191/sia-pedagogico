<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\CourseSection;
use App\Models\Classroom;
use App\Models\TimeSlot;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\StudyPlan;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function edit(CourseSection $courseSection)
    {
        $courseSection->load(['course.studyPlan.studyProgram', 'academicPeriod']);

        // 1. Buscamos cursos del mismo Periodo, Plan, Ciclo, Nombre Y TURNO
        $sections = CourseSection::with(['course', 'teacher'])
            ->where('academic_period_id', $courseSection->academic_period_id)
            ->where('name', $courseSection->name)
            ->where('shift_id', $courseSection->shift_id) // <--- Filtro de turno
            ->whereHas('course', function($q) use ($courseSection) {
                $q->where('study_plan_id', $courseSection->course->study_plan_id)
                ->where('cycle', $courseSection->course->cycle);
            })->get();

        // 2. Filtramos los bloques horarios (TimeSlots) según el turno de la sección
        // Si shift_id es 1 (Mañana), traemos solo mañana. Si es 2 (Tarde), solo tarde.
        $turnoNombre = ($courseSection->shift_id == 1) ? 'mañana' : 'tarde';

        $timeSlots = \App\Models\TimeSlot::where('shift', $turnoNombre)
            ->orderBy('start_time', 'asc') // <--- AHORA SÍ SALDRÁN EN ORDEN DE HORA
            ->get();

        return Inertia::render('Admin/Schedules/Editor', [
            'sections' => $sections,
            'plan' => $courseSection->course->studyPlan->load('studyProgram'),
            'cycle' => $courseSection->course->cycle,
            'sectionName' => $courseSection->name,
            'shiftId' => $courseSection->shift_id, // <--- Enviamos el turno a la vista
            'academicPeriod' => $courseSection->academicPeriod,
            'timeSlots' => $timeSlots, // <--- Solo los bloques que corresponden
            'classrooms' => \App\Models\Classroom::where('is_active', true)->get(),
            'currentSchedules' => \App\Models\Schedule::whereIn('course_section_id', $sections->pluck('id'))
                ->with(['course', 'classroom', 'section.teacher'])
                ->get(),
            'days' => [
                ['id' => 1, 'name' => 'Lunes'], ['id' => 2, 'name' => 'Martes'],
                ['id' => 3, 'name' => 'Miércoles'], ['id' => 4, 'name' => 'Jueves'],
                ['id' => 5, 'name' => 'Viernes'],
            ]
        ]);
    }
    // El parámetro $courseSection debe estar aquí para recibir el ID de la URL
    public function store(Request $request, CourseSection $courseSection)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:1,5',
            'time_slot_id' => 'required|exists:time_slots,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        // Validamos choques usando el docente de ESTA sección
        $conflicts = $this->scheduleService->checkConflicts(
            $courseSection->teacher_id, // Usamos el ID de la sección de la URL
            $validated['classroom_id'],
            $validated['day_of_week'],
            $validated['time_slot_id'],
            $courseSection->academic_period_id
        );

        if (count($conflicts) > 0) {
            return back()->withErrors(['collision' => $conflicts[0]]);
        }

        // Guardamos el bloque
        Schedule::create([
            'course_section_id' => $courseSection->id,
            'course_id' => $courseSection->course_id,
            'teacher_id' => $courseSection->teacher_id,
            'academic_period_id' => $courseSection->academic_period_id,
            'classroom_id' => $validated['classroom_id'],
            'time_slot_id' => $validated['time_slot_id'],
            'day_of_week' => $validated['day_of_week'],
        ]);

        return back()->with('success', 'Bloque asignado.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Bloque horario eliminado.');
    }
}
