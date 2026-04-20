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
        $teacherBusy = Schedule::where('academic_period_id', $periodId)
            ->where('teacher_id', $teacherId)
            ->where('day_of_week', $day)
            ->where('time_slot_id', $slotId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->first();

        if ($teacherBusy) {
            $conflicts[] = "El docente ya tiene una clase asignada en este horario.";
        }

        // 2. VALIDACIÓN: Disponibilidad del Docente (Zonas Rojas)
        $isUnavailable = TeacherAvailability::where('teacher_id', $teacherId)
            ->where('day_of_week', $day)
            ->where('time_slot_id', $slotId)
            ->where('is_available', false)
            ->exists();

        if ($isUnavailable) {
            $conflicts[] = "El docente marcó este horario como NO DISPONIBLE en su declaración.";
        }

        // 3. VALIDACIÓN: Cruce de Aula
        if ($classroomId) {
            $roomOccupied = Schedule::where('academic_period_id', $periodId)
                ->where('classroom_id', $classroomId)
                ->where('day_of_week', $day)
                ->where('time_slot_id', $slotId)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists();

            if ($roomOccupied) {
                $conflicts[] = "El ambiente (aula/lab) ya está ocupado por otra sección en este horario.";
            }
        }

        return $conflicts;
    }
}
