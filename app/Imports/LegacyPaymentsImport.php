<?php

namespace App\Imports;

use App\Models\Payment;
use App\Models\Person;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class LegacyPaymentsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public $reporte = ['procesados' => 0, 'omitidos' => 0, 'errores' => []];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $filaNum = $index + 2;

            // Laravel Excel convierte "NUM. DOC" en "num_doc" y "FECHA" en "fecha" automáticamente
            $estado = strtoupper(trim($row['estado'] ?? ''));
            $dni = trim($row['num_doc'] ?? '');
            $monto = floatval($row['total'] ?? 0);

            // --- REGLA DE ORO: Solo importamos lo ACEPTADO y con monto positivo ---
            if ($estado !== 'ACEPTADO' || $monto <= 0) {
                $this->reporte['omitidos']++;
                continue;
            }

            try {
                $person = Person::where('dni', $dni)->first();

                if (!$person) {
                    $this->reporte['errores'][] = "Fila {$filaNum}: DNI {$dni} ({$row['denominacion']}) no existe en el sistema.";
                    $this->reporte['omitidos']++;
                    continue;
                }

                Payment::create([
                    'person_id' => $person->id,
                    'payment_concept_id' => 99, // Concepto LEGADO
                    'concept' => 'PAGO HISTÓRICO MIGRADO',
                    'amount' => $monto,
                    'operation_number' => trim($row['serie']) . '-' . trim($row['numero']),
                    'external_serie' => trim($row['serie']),
                    'external_number' => trim($row['numero']),
                    'status' => 'approved',
                    'is_imported' => true,
                    // Usamos la fecha del Excel. Si falla, ponemos la de hoy.
                    'created_at' => $this->transformDate($row['fecha']),
                    'updated_at' => now(),
                ]);

                $this->reporte['procesados']++;

            } catch (\Exception $e) {
                $this->reporte['errores'][] = "Fila {$filaNum}: " . $e->getMessage();
            }
        }
    }

    // Función para manejar las fechas locas de Excel
    private function transformDate($value) {
        try {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat('d/m/Y', $value);
        } catch (\Exception $e) {
            return now();
        }
    }

    public function chunkSize(): int { return 500; }
}
