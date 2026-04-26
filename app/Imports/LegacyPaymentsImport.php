<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Models\Payment;

class LegacyPaymentsImport implements ToCollection, WithHeadingRow
{
    public $reporte = [
        'procesados' => 0,
        'omitidos'   => 0,
        'errores'    => [],
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Normalizar datos (RF-22.1: Mapeo Inteligente)
            $dni    = trim(strtoupper($row['dni'] ?? ''));
            $monto  = trim($row['monto'] ?? '');
            $fecha  = trim($row['fecha'] ?? '');

            if (empty($dni) || empty($monto)) {
                $this->reporte['omitidos']++;
                $this->reporte['errores'][] = "Fila incompleta: " . json_encode($row);
                continue;
            }

            $person = \App\Models\Person::where('dni', $dni)->first();
            if (!$person) {
                $this->reporte['omitidos']++;
                $this->reporte['errores'][] = "Persona no encontrada para DNI: $dni";
                continue;
            }

            Payment::create([
                'person_id' => $person->id,
                'amount'    => $monto,
                'date'      => $fecha,
            ]);

            $this->reporte['procesados']++;
        }
    }
}
