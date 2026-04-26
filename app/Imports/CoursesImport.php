<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Models\Course;

class CoursesImport implements ToCollection, WithHeadingRow
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
            $codigo = trim(strtoupper($row['codigo'] ?? ''));
            $nombre = trim(strtoupper($row['nombre'] ?? ''));

            if (empty($codigo)) {
                $this->reporte['errores'][] = "Fila sin código: " . json_encode($row);
                continue;
            }

            $course = Course::updateOrCreate(
                ['code' => $codigo],
                ['name' => $nombre]
            );

            if ($course->wasRecentlyCreated) {
                $this->reporte['creados']++;
            } else {
                $this->reporte['actualizados']++;
            }
        }
    }
}
