<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\TeacherAvailability;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    /**
     * Verifica si hay conflictos para asignar una clase.
     */
    public function checkConflicts($teacherId, $classroomId, $day, $slotId, $periodId, $excludeId = null)
    {
        $conflicts = [];

        // 1. VALIDACIÓN: Cruce de Docente (RN-012)
        if ($teacherId) {
            // Agregamos with() para traer los nombres del curso y sección en conflicto
            $teacherBusy = Schedule::with(['course', 'section'])
                ->where('academic_period_id', $periodId)
                ->where('teacher_id', $teacherId)
                ->where('day_of_week', $day)
                ->where('time_slot_id', $slotId)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->first();

            if ($teacherBusy) {
                // Mensaje detallado: Docente ocupado en [CURSO] - SECC [A]
                $conflicts[] = "DOCENTE OCUPADO: Ya dicta '{$teacherBusy->course->name}' en la sección '{$teacherBusy->section->name}'.";
            }
        }

        // 2. VALIDACIÓN: Disponibilidad del Docente (Zonas Rojas)
        if ($teacherId) {
            $isUnavailable = TeacherAvailability::where('teacher_id', $teacherId)
                ->where('day_of_week', $day)
                ->where('time_slot_id', $slotId)
                ->where('is_available', false)
                ->exists();

            if ($isUnavailable) {
                $conflicts[] = "NO DISPONIBLE: El docente marcó este bloque como prohibido en su declaración jurada.";
            }
        }

        // 3. VALIDACIÓN: Cruce de Aula
        if ($classroomId) {
            $roomOccupied = Schedule::with(['course', 'section'])
                ->where('academic_period_id', $periodId)
                ->where('classroom_id', $classroomId)
                ->where('day_of_week', $day)
                ->where('time_slot_id', $slotId)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->first();

            if ($roomOccupied) {
                // Mensaje detallado: Aula ocupada por [CURSO] (Secc. A)
                $conflicts[] = "AULA OCUPADA: Aquí se dicta '{$roomOccupied->course->name}' (Secc. {$roomOccupied->section->name}).";
            }
        }

        return $conflicts;
    }
}
