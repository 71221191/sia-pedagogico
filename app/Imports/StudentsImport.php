<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Models\Person;

class StudentsImport implements ToCollection, WithHeadingRow
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
            // Normalizar datos
            $dni   = trim(strtoupper($row['dni'] ?? ''));
            $nombres = trim(strtoupper($row['nombres'] ?? ''));
            $apellidos = trim(strtoupper($row['apellidos'] ?? ''));

            // Validar que el DNI tenga exactamente 8 dígitos numéricos
            if (empty($dni) || !preg_match('/^\d{8}$/', $dni)) {
                $this->reporte['errores'][] = "Fila con DNI inválido (debe tener 8 dígitos): " . json_encode($row);
                continue;
            }

            // Buscar o crear persona
            $person = Person::updateOrCreate(
                ['dni' => $dni],
                [
                    'nombres'   => $nombres,
                    'apellidos' => $apellidos,
                ]
            );

            if ($person->wasRecentlyCreated) {
                $this->reporte['creados']++;
            } else {
                $this->reporte['actualizados']++;
            }
        }
    }
}
