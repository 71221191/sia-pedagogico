<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use App\Models\TeacherAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AvailabilityController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->person;

        return Inertia::render('Teacher/Availability/Index', [
            // Traemos los bloques de tiempo (Mañana y Tarde)
            'timeSlots' => TimeSlot::orderBy('shift')->orderBy('start_time')->get(),
            // Traemos lo que el profe ya guardó
            'currentAvailability' => TeacherAvailability::where('teacher_id', $teacher->id)->get(),
            'days' => [
                ['id' => 1, 'name' => 'Lunes'],
                ['id' => 2, 'name' => 'Martes'],
                ['id' => 3, 'name' => 'Miércoles'],
                ['id' => 4, 'name' => 'Jueves'],
                ['id' => 5, 'name' => 'Viernes'],
            ]
        ]);
    }

    public function store(Request $request)
    {
        $teacher = Auth::user()->person;

        // Recibimos un array de celdas marcadas como "No disponibles"
        $unavailableSlots = $request->input('unavailable_slots'); // [{day: 1, slot_id: 5}, ...]

        DB::transaction(function () use ($teacher, $unavailableSlots) {
            // 1. Limpiamos disponibilidad anterior para sobrescribir
            TeacherAvailability::where('teacher_id', $teacher->id)->delete();

            // 2. Guardamos solo las "Zonas Rojas" (No disponibles)
            foreach ($unavailableSlots as $slot) {
                TeacherAvailability::create([
                    'teacher_id' => $teacher->id,
                    'day_of_week' => $slot['day'],
                    'time_slot_id' => $slot['slot_id'],
                    'is_available' => false
                ]);
            }
        });

        return back()->with('success', 'Disponibilidad actualizada correctamente.');
    }
}
