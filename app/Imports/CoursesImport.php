<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\StudyPlan;
use App\Models\StudyProgram;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use App\Traits\StandardizesAcademicData; // Importante: Tu colador de datos

class CoursesImport implements ToCollection, WithHeadingRow
{
    use StandardizesAcademicData;

    public $reporte = [
        'creados' => 0,
        'actualizados' => 0,
        'omitidos' => 0,
        'errores' => []
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $filaNum = $index + 2;

            try {
                // 1. VALIDACIÓN INICIAL
                if (empty($row['programa']) || (empty($row['curso']) && empty($row['nombre']))) {
                    $this->reporte['omitidos']++;
                    continue;
                }

                // 2. OBTENER PROGRAMA Y PLAN (Usando el colador del Trait)
                $info = $this->extraerInfoPrograma($row['programa']);

                $program = StudyProgram::firstOrCreate([
                    'name' => $info['programa']
                ]);

                $plan = StudyPlan::firstOrCreate(
                    [
                        'study_program_id' => $program->id,
                        'resolution_code' => $info['resolucion']
                    ],
                    [
                        'name' => 'Plan ' . $info['resolucion'],
                        'evaluation_type' => 'competency',
                        'valid_from_year' => 2019,
                        'is_active' => true
                    ]
                );

                // 3. PROCESAR DATOS DEL CURSO
                $courseName = trim($row['curso'] ?? $row['nombre'] ?? '');
                $cicloStr = trim($row['ciclo'] ?? '1');

                // Normalizamos el Ciclo (I -> 1, II -> 2, etc.)
                $ciclo = $this->traducirCicloANumero($cicloStr);
                $slug = $this->normalizarSlug($courseName);


                // Generamos la huella digital para evitar duplicados (Sin tildes, sin espacios)
                $huellaNueva = $this->generarHuellaCurso($courseName);

                // Buscamos si el curso ya existe en este Plan para no duplicarlo
                $course = Course::where('study_plan_id', $plan->id)->where('slug', $slug)->first();


                // Preparamos los datos que vienen del PDF/Excel
                $datosCargados = [
                    'name'           => mb_strtoupper($courseName, 'UTF-8'), // <--- Forzamos Mayúsculas con tildes
                    'slug'           => $slug,                               // <--- AGREGAMOS ESTA LÍNEA
                    'cycle'          => $ciclo,
                    'credits'        => is_numeric($row['cr'] ?? 0) ? ($row['cr'] ?? 0) : 0,
                    'hours_total'    => is_numeric($row['h'] ?? 0) ? ($row['h'] ?? 0) : 0,
                    'hours_theory'   => is_numeric($row['t'] ?? 0) ? ($row['t'] ?? 0) : 0,
                    'hours_practice' => is_numeric($row['p'] ?? 0) ? ($row['p'] ?? 0) : 0,
                    'component'      => strtoupper(trim($row['componente'] ?? 'FG')),
                    'type'           => $this->mapearTipoCurso($row['componente'] ?? ''),
                    'is_legacy'      => ($info['resolucion'] == 'PLAN ANTIGUO'),
                ];

                if ($course) {
                    // ACTUALIZACIÓN: Si ya existe, le metemos los datos del PDF
                    $this->reporte['actualizados']++;
                    $course->update($datosCargados);
                } else {
                    // CREACIÓN: Si es nuevo, lo registramos desde cero
                    $this->reporte['creados']++;
                    $datosCargados['study_plan_id'] = $plan->id;
                    $datosCargados['name'] = strtoupper($courseName);
                    // Usamos el código del Excel o generamos uno aleatorio
                    $datosCargados['code'] = $row['codigo'] ?? (mb_substr(Str::ascii($courseName), 0, 3) . rand(100, 999));

                    Course::create($datosCargados);
                }

            } catch (\Exception $e) {
                $this->reporte['errores'][] = "Fila {$filaNum}: " . $e->getMessage();
            }
        }
    }

    /**
     * Mapea el componente (FG, FE, FPI) al tipo de curso de la base de datos
     */
    private function mapearTipoCurso($componente)
    {
        $componente = strtoupper(trim($componente));

        return match ($componente) {
            'FG' => 'general',
            'FE', 'FPI' => 'specific',
            'ELECTIVOS', 'ELECTIVO' => 'elective',
            default => 'specific',
        };
    }
}
