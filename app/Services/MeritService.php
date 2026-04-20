<?php

namespace App\Services;

use App\Models\Person;
use App\Models\AcademicPeriod;
use Illuminate\Support\Facades\DB;

class MeritService
{
    public function updateAllRankings($periodId = null)
    {
        // Si no viene ID, buscamos el que esté abierto
        if (!$periodId) {
            $period = AcademicPeriod::where('status', 'open')->first();
            $periodId = $period?->id;
        }

        if (!$periodId) return "No hay periodo para procesar.";

        // 1. Obtener alumnos del periodo
        $students = Person::whereHas('enrollments', function($q) use ($periodId) {
            $q->where('academic_period_id', $periodId);
        })->get();

        foreach ($students as $student) {
            // CAMBIO CRÍTICO: Calculamos el ACUMULADO (Toda la historia del alumno)
            $stats = DB::table('enrollment_details')
                ->join('courses', 'enrollment_details.course_id', '=', 'courses.id')
                ->join('enrollments', 'enrollment_details.enrollment_id', '=', 'enrollments.id')
                ->where('enrollments.person_id', $student->id)
                ->where('enrollment_details.status', 'approved')
                // Quitamos el filtro de periodo para que sume TODO el pasado
                ->select(
                    DB::raw('SUM(final_score_numeric * credits) as total_points'),
                    DB::raw('SUM(credits) as total_credits')
                )->first();

            if ($stats && $stats->total_credits > 0) {
                $average = $stats->total_points / $stats->total_credits;

                // Guardamos el promedio ACUMULADO en la foto del periodo
                DB::table('academic_rankings')->updateOrInsert(
                    ['person_id' => $student->id, 'academic_period_id' => $periodId],
                    [
                        'weighted_average' => round($average, 4),
                        'calculated_at' => now(),
                        'position' => 0,
                        'total_students' => 0,
                        'updated_at' => now()
                    ]
                );
            }
        }

        $this->assignPositions($periodId);
    }

    private function assignPositions($periodId)
    {
        // 1. Obtenemos el año del periodo que estamos procesando (ej: 2025)
        $currentPeriod = DB::table('academic_periods')->find($periodId);
        $year = date('Y', strtotime($currentPeriod->start_date));

        // 2. Obtenemos los rankings que acabamos de calcular
        $rankings = DB::table('academic_rankings')
            ->where('academic_period_id', $periodId)
            ->get();

        foreach ($rankings as $row) {
            $student = Person::find($row->person_id);
            $lastEnrollment = $student->enrollments()->where('academic_period_id', $periodId)->first();

            if (!$lastEnrollment) continue;

            // 3. LA CLAVE: Buscamos compañeros del MISMO CICLO y CARRERA
            // pero que su periodo de matricula haya empezado en el MISMO AÑO
            $peers = DB::table('academic_rankings')
                ->join('enrollments', 'academic_rankings.person_id', '=', 'enrollments.person_id')
                ->join('academic_periods', 'enrollments.academic_period_id', '=', 'academic_periods.id')
                ->where('enrollments.study_plan_id', $lastEnrollment->study_plan_id)
                ->where('enrollments.cycle', $lastEnrollment->cycle)
                ->whereYear('academic_periods.start_date', $year) 
                ->select('academic_rankings.person_id', 'academic_rankings.weighted_average')
                ->distinct()
                ->get();

            $totalPeers = $peers->count();

            // 4. Calcular posición (Comparando promedios)
            $position = $peers->where('weighted_average', '>', $row->weighted_average)->count() + 1;

            // 5. Actualizamos con la verdad de su promoción anual
            DB::table('academic_rankings')->where('id', $row->id)->update([
                'position' => $position,
                'total_students' => $totalPeers
            ]);
        }
    }
}
