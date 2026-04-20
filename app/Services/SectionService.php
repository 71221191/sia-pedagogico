<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Support\Facades\DB;

class SectionService
{
    /**
     * Genera secciones base para un conjunto de ciclos y un plan de estudios.
     */
    public function generateBulkSections(int $periodId, int $planId, array $cycles)
    {
        return DB::transaction(function () use ($periodId, $planId, $cycles) {

            // 1. MAPA DE EQUIVALENCIAS: El "Cerebro" del traductor
            $equivalencias = [
                'I'    => ['I', '1'],
                'II'   => ['II', '2'],
                'III'  => ['III', '3'],
                'IV'   => ['IV', '4'],
                'V'    => ['V', '5'],
                'VI'   => ['VI', '6'],
                'VII'  => ['VII', '7'],
                'VIII' => ['VIII', '8'],
                'IX'   => ['IX', '9'],
                'X'    => ['X', '10'],
            ];

            // 2. Expandimos la búsqueda: si eligió 'I', buscamos ['I', '1']
            $ciclosBusqueda = [];
            foreach ($cycles as $c) {
                if (isset($equivalencias[$c])) {
                    $ciclosBusqueda = array_merge($ciclosBusqueda, $equivalencias[$c]);
                } else {
                    $ciclosBusqueda[] = $c;
                }
            }

            // 3. Buscamos los cursos usando la lista expandida
            $courses = Course::where('study_plan_id', $planId)
                ->whereIn('cycle', $ciclosBusqueda) // <--- Aquí está la magia
                ->get();

            $createdCount = 0;

            foreach ($courses as $course) {
                // 4. Estandarización de Salida:
                // Al crear la sección, siempre la guardamos con el formato Romano
                // para que el sistema se vea ordenado de aquí en adelante.
                $section = CourseSection::firstOrCreate(
                    [
                        'course_id' => $course->id,
                        'academic_period_id' => $periodId,
                        'name' => 'A',
                    ],
                    [
                        'vacancy_limit' => 30,
                        'is_closed' => false,
                    ]
                );

                if ($section->wasRecentlyCreated) {
                    $createdCount++;
                }
            }

            return $createdCount;
        });
    }
}
