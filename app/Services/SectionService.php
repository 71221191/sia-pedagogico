<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Support\Facades\DB;

class SectionService
{
    /**
     * Genera secciones base para un conjunto de ciclos, un plan de estudios y varias etiquetas (A, B, C).
     */
    public function generateBulkSections(int $periodId, int $planId, array $cycles, array $sectionLabels, int $shiftId)
    {
        return DB::transaction(function () use ($periodId, $planId, $cycles, $sectionLabels, $shiftId) {

            // 1. MAPA DE EQUIVALENCIAS (Mantenemos tu lógica de romanos/números)
            $equivalencias = [
                'I'    => ['I', '1'], 'II'   => ['II', '2'], 'III'  => ['III', '3'],
                'IV'   => ['IV', '4'], 'V'    => ['V', '5'], 'VI'   => ['VI', '6'],
                'VII'  => ['VII', '7'], 'VIII' => ['VIII', '8'], 'IX'   => ['IX', '9'],
                'X'    => ['X', '10'],
            ];

            $ciclosBusqueda = [];
            foreach ($cycles as $c) {
                if (isset($equivalencias[$c])) {
                    $ciclosBusqueda = array_merge($ciclosBusqueda, $equivalencias[$c]);
                } else {
                    $ciclosBusqueda[] = $c;
                }
            }

            // 2. Buscamos los cursos del plan
            $courses = Course::where('study_plan_id', $planId)
                ->whereIn('cycle', $ciclosBusqueda)
                ->get();

            $createdCount = 0;

            // 3. DOBLE BUCLE: Por cada curso y por cada letra de sección
            foreach ($courses as $course) {
                foreach ($sectionLabels as $label) {

                    // Usamos firstOrCreate para evitar duplicados si vuelves a correr el proceso
                    $section = CourseSection::firstOrCreate(
                        [
                            'course_id'          => $course->id,
                            'academic_period_id' => $periodId,
                            'name'               => strtoupper(trim($label)),
                            'shift_id'           => $shiftId, // <--- ASIGNAMOS EL TURNO AQUÍ
                        ],
                        [
                            'vacancy_limit' => 30,
                            'is_closed'     => false,
                        ]
                    );

                    if ($section->wasRecentlyCreated) {
                        $createdCount++;
                    }
                }
            }

            return $createdCount;
        });
    }
}
