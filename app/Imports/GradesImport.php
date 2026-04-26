<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Models\Grade;

class GradesImport implements ToCollection, WithHeadingRow
{
    public $reporte = [
        'creados'     => 0,
        'actualizados'=> 0,
        'omitidos'    => 0,
        'errores'     => [],
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Normalizar datos (RF-22.1: Mapeo Inteligente)
            $dni       = trim(strtoupper($row['dni'] ?? ''));
            $codigo    = trim(strtoupper($row['codigo_curso'] ?? ''));
            $nota      = trim($row['nota'] ?? '');

            if (empty($dni) || empty($codigo)) {
                $this->reporte['errores'][] = "Fila incompleta: " . json_encode($row);
                continue;
            }

            // Buscar persona y curso (asumimos que existen)
            $person = \App\Models\Person::where('dni', $dni)->first();
            $course = \App\Models\Course::where('code', $codigo)->first();

            if (!$person || !$course) {
                $this->reporte['errores'][] = "Persona o curso no encontrado para DNI: $dni, código: $codigo";
                continue;
            }

            $grade = Grade::updateOrCreate(
                [
                    'person_id' => $person->id,
                    'course_id' => $course->id,
                ],
                [
                    'grade' => $nota,
                ]
            );

            if ($grade->wasRecentlyCreated) {
                $this->reporte['creados']++;
            } else {
                $this->reporte['actualizados']++;
            }
        }
    }
}
