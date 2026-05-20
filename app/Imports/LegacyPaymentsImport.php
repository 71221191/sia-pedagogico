<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Row;
use App\Models\Payment;
use App\Models\Person;
use App\Models\AcademicPeriod;
use App\Services\EnrollmentService;
use App\Traits\TracksImportResults;
use Illuminate\Support\Facades\DB;

class LegacyPaymentsImport implements OnEachRow, WithChunkReading, WithEvents
{
    use TracksImportResults;

    private $filename;
    private $console;
    private $progressBar;

    public function __construct($filename, $console = null)
    {
        $this->filename = $filename;
        $this->console = $console;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                if ($this->console) {
                    $totalRows = $event->getReader()->getTotalRows();
                    $actualCount = reset($totalRows) - 1;
                    $this->progressBar = $this->console->getOutput()->createProgressBar($actualCount);
                    $this->progressBar->start();
                }
            },
            AfterImport::class => function(AfterImport $event) {
                // Forzamos el guardado del historial
                $this->saveImportHistory($this->filename, 'payments');
            },
        ];
    }

    public function onRow(Row $row)
    {
        $index = $row->getIndex();
        $rowData = $row->toArray();

        if ($index === 1) return; // Saltar cabecera

        if ($this->progressBar) {
            $this->progressBar->advance();
        }

        // Mapeo por posición: F=5 (DNI), J=9 (Total), D=3 (Serie), E=4 (Número)
        // Convertimos a string por si Excel lo manda como número
        $dni    = isset($rowData[5]) ? (string)$rowData[5] : '';
        $monto  = $rowData[9] ?? 0;
        $serie  = $rowData[3] ?? '';
        $numero = $rowData[4] ?? '';
        $fechaExcel = $rowData[1] ?? null; // Columna B
        // Convertimos la fecha de 05/05/2026 a formato base de datos
        $paidAt = $fechaExcel ? \Carbon\Carbon::createFromFormat('d/m/Y', $fechaExcel) : now();

        if (empty($dni) || strlen($dni) < 5) return;

        try {
            // Buscamos a la persona
            $person = Person::where('dni', $dni)->first();

            if (!$person) {
                $errorMsg = "Error en Fila {$index}: El DNI {$dni} NO EXISTE en la tabla people.";

                // --- DEBUG EN CONSOLA ---
                if ($this->c_errors === 0 && $this->console) {
                    $this->console->error("\n" . $errorMsg);
                }

                $this->recordRowResult($rowData, 'ERROR', $errorMsg);
                return;
            }

            $status = '';
            $msg = '';

            DB::transaction(function () use ($person, $monto, $serie, $numero, $paidAt, &$status, &$msg) {
                $payment = Payment::updateOrCreate(
                    ['operation_number' => $serie . '-' . $numero],
                    [
                        'person_id' => $person->id,
                        'concept' => ($monto >= 150) ? 'MATRÍCULA' : 'TRÁMITE', // Ajustado a tus 150
                        'amount' => $monto,

                        'external_serie' => $serie,
                        'external_number' => $numero,

                        'paid_at' => $paidAt,

                        'status' => 'approved',
                        'is_imported' => true,
                        'verified_at' => now(),
                    ]
                );
                // --- EL FILTRO DE SEGURIDAD QUE PEDISTE ---
                $periodoAbierto = AcademicPeriod::where('status', 'open')->first();

                // SOLO si el pago cae dentro de las fechas del periodo abierto, disparamos la matrícula
                if ($periodoAbierto && $paidAt->between($periodoAbierto->start_date, $periodoAbierto->end_date)) {
                    if ($payment->concept === 'MATRÍCULA') {
                        // Llamamos a la automatización que creamos arriba
                        (new EnrollmentService)->autoEnrollStudentByPayment($person, $periodoAbierto);
                    }
                }
                // ------------------------------------------

                $status = $payment->wasRecentlyCreated ? 'CREADO' : 'ACTUALIZADO';
            });

            $this->recordRowResult($rowData, $status, $msg);

        } catch (\Exception $e) {
            if ($this->c_errors === 0 && $this->console) {
                $this->console->error("\n Error Crítico: " . $e->getMessage());
            }
            $this->recordRowResult($rowData, 'ERROR', $e->getMessage());
        }
    }

    public function chunkSize(): int { return 200; }

    public function getReporte() {
        if ($this->progressBar) $this->progressBar->finish();
        return [
            'creados' => $this->c_created,
            'actualizados' => $this->c_updated,
            'omitidos' => $this->c_omitted,
            'errores_count' => $this->c_errors,
        ];
    }
}
