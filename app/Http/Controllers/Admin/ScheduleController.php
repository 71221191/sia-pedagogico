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

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function edit(CourseSection $courseSection)
    {
        // Cargamos la data necesaria para el constructor
        $courseSection->load(['course', 'teacher', 'academicPeriod']);

        return Inertia::render('Admin/Schedules/Editor', [
            'section' => $courseSection,
            'timeSlots' => TimeSlot::orderBy('shift')->orderBy('start_time')->get(),
            'classrooms' => Classroom::where('is_active', true)->get(),
            'currentSchedules' => Schedule::where('course_section_id', $courseSection->id)->get(),
            'days' => [
                ['id' => 1, 'name' => 'Lunes'],
                ['id' => 2, 'name' => 'Martes'],
                ['id' => 3, 'name' => 'Miércoles'],
                ['id' => 4, 'name' => 'Jueves'],
                ['id' => 5, 'name' => 'Viernes'],
            ]
        ]);
    }

    public function store(Request $request, CourseSection $courseSection)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:1,5',
            'time_slot_id' => 'required|exists:time_slots,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        // 1. Usamos el servicio para validar choques
        $conflicts = $this->scheduleService->checkConflicts(
            $courseSection->teacher_id,
            $validated['classroom_id'],
            $validated['day_of_week'],
            $validated['time_slot_id'],
            $courseSection->academic_period_id
        );

        if (count($conflicts) > 0) {
            return back()->withErrors(['collision' => $conflicts[0]]);
        }

        // 2. Si todo está bien, guardamos el bloque
        Schedule::create([
            'course_section_id' => $courseSection->id,
            'course_id' => $courseSection->course_id,
            'teacher_id' => $courseSection->teacher_id,
            'academic_period_id' => $courseSection->academic_period_id,
            'classroom_id' => $validated['classroom_id'],
            'time_slot_id' => $validated['time_slot_id'],
            'day_of_week' => $validated['day_of_week'],
        ]);

        return back()->with('success', 'Bloque horario asignado.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Bloque horario eliminado.');
    }
}
