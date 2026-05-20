<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\StudyPlan;
use App\Models\StudyProgram;
use App\Traits\StandardizesAcademicData;
use App\Traits\TracksImportResults;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoursesImport implements ToCollection, WithHeadingRow
{
    // Cargamos las dos mochilas de herramientas: rastreo y estandarización
    use TracksImportResults, StandardizesAcademicData;

    private $filename;
    private $competencyMap;

    /**
     * Constructor para recibir el nombre del archivo desde el controlador
     */
    public function __construct($filename = 'importacion_cursos.xlsx')
    {
        $this->filename = $filename;

        // Buscamos todas las competencias y limpiamos el nombre para que solo quede el número
        // Ejemplo: "Competencia 1" se convierte en "1"
        $this->competencyMap = \App\Models\Competency::all()->mapWithKeys(function ($item) {
            // Esta expresión regular busca el último número en la cadena
            // Así, de "Competencia 12" saca "12", de "C1" saca "1".
            preg_match('/(\d+)/', $item->code, $matches);
            $number = isset($matches[0]) ? (string)$matches[0] : null;

            if ($number) {
                return [$number => $item->id];
            }
            return [];
        })->toArray();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // 1. Limpieza inicial de datos del Excel
            // Laravel Excel convierte "PROGRAMA" en "programa", "ciclo" en "ciclo", etc.
            $nombreRaw = $row['nombre'] ?? $row['name'] ?? '';
            $codigoExcel = trim(strtoupper($row['codigo'] ?? $row['code'] ?? ''));
            $programaRaw = $row['programa'] ?? '';

            // Convertimos el ciclo a número (I -> 1, II -> 2, etc.)
            $cicloNum = $this->traducirCicloANumero($row['ciclo'] ?? '1');

            // Si el nombre está vacío, omitimos la fila
            if (empty($nombreRaw)) {
                $this->recordRowResult($row->toArray(), 'OMITIDO', 'El nombre del curso está vacío');
                continue;
            }

            try {
                $status = '';
                $msg = '';
                // Generamos la "Huella" (slug) para que "Matemática I" sea igual a "matematica-i"
                $huella = $this->generarHuellaCurso($nombreRaw);

                // --- 1. DETECTAR COMPETENCIAS C1 A C12 EN ESTA FILA ---
                // Recorremos las columnas del Excel buscando donde pusiste un "1"
                $competencyIdsToSync = [];
                for ($i = 1; $i <= 12; $i++) {
                    $colName = "c$i";

                    // Verificamos si en el Excel pusiste un "1" en la columna exacta
                    if (isset($row[$colName]) && (int)$row[$colName] === 1) {
                        $searchKey = (string)$i; // Aquí $i será "1", "2"... "10", "11", "12"

                        // La magia está aquí: isset() busca la llave EXACTA en el diccionario
                        if (isset($this->competencyMap[$searchKey])) {
                            $competencyIdsToSync[] = $this->competencyMap[$searchKey];
                        }
                    }
                }

                // --- 2. INICIAR PROCESO DE GUARDADO ---
                DB::transaction(function () use ($row, $programaRaw, $huella, $nombreRaw, $cicloNum, $codigoExcel, $competencyIdsToSync, &$status, &$msg) {

                    // A. PROCESO DE PLAN Y PROGRAMA
                    $info = $this->extraerInfoPrograma($programaRaw);
                    $program = \App\Models\StudyProgram::firstOrCreate(['name' => $info['programa']]);

                    $plan = \App\Models\StudyPlan::firstOrCreate(
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

                    // B. PROCESO DEL CURSO (La Llave Maestra: ID_PLAN + HUELLA)
                    $codigoFinal = $codigoExcel ?: $this->generarCodigoCurso($plan->id, $cicloNum);

                    $course = Course::updateOrCreate(
                        [
                            'study_plan_id' => $plan->id,
                            'slug' => $huella // Evita duplicados
                        ],
                        [
                            'code' => $codigoFinal,
                            'name' => strtoupper(trim($nombreRaw)),
                            'cycle' => $cicloNum,
                            'credits' => $row['cr'] ?? $row['credits'] ?? 0,
                            'hours_total' => $row['h'] ?? 0,
                            'hours_theory' => $row['t'] ?? 0,
                            'hours_practice' => $row['p'] ?? 0,
                            'type' => (str_contains($huella, 'electivo')) ? 'elective' : 'specific',
                        ]
                    );

                    // --- C. MAGIA: VINCULAR LAS COMPETENCIAS DETECTADAS AL CURSO ---
                    // El método sync() borra las anteriores y pone las nuevas del Excel.
                    // Esto crea el "Mapa Curricular sugerido" que el docente validará.
                    $course->competencies()->sync($competencyIdsToSync);

                    if ($course->wasRecentlyCreated) {
                        $status = 'CREADO';
                        $msg = 'Curso y Mapa Curricular registrados exitosamente.';
                    } else {
                        $status = 'ACTUALIZADO';
                        $msg = 'Información y Mapa Curricular sincronizados.';
                    }
                });

                // Registramos éxito en el historial (Trait)
                $this->recordRowResult($row->toArray(), $status, $msg);

            } catch (\Exception $e) {
                // Registramos error en el historial si algo falla (ej: falta una columna obligatoria)
                $this->recordRowResult($row->toArray(), 'ERROR', $e->getMessage());
            }
        }
        // Al finalizar todas las filas, guardamos el rastro en la tabla legacy_imports
        $this->saveImportHistory($this->filename, 'courses');
    }

    /**
     * Devuelve el resumen de la operación para el controlador
     */
    public function getReporte()
    {
        return [
            'creados' => $this->c_created,
            'actualizados' => $this->c_updated,
            'omitidos' => $this->c_omitted,
            'errores_count' => $this->c_errors,
        ];
    }
}
