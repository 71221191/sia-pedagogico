<?php

namespace App\Imports;

use App\Models\{Person, StudyProgram, StudyPlan, AcademicPeriod, Enrollment, User, Shift, Language, DisabilityType, SocioeconomicFile, Ubigeo};
use Maatwebsite\Excel\Concerns\{ToCollection, WithHeadingRow, WithEvents};
use Maatwebsite\Excel\Events\{AfterImport};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Hash, DB, Cache};
use App\Traits\StandardizesAcademicData;
use Carbon\Carbon;
use App\Traits\TracksImportResults;

class ActiveStudentsImport implements ToCollection, WithHeadingRow, WithEvents
{
    // Ahora la clase "carga" las funciones de ambos Traits
    use StandardizesAcademicData, TracksImportResults;

    // 1. Le decimos que es un 'array'
    public array $reporte = [
        'creados' => 0,
        'actualizados' => 0,
        'omitidos' => 0,
        'errores' => [],
    ];

    // 2. Definimos los tipos de las variables privadas
    private string $importId;
    private $filename;
    private string $fixedPassword;
    private array $languages;
    private array $ethnicities;
    private array $ubigeos;
    private array $maritalStatuses;
    private array $disabilityTypes;

    // 3. En el constructor especificamos que $importId es un string (texto)
    public function __construct($importId, $filename = 'importacion.xlsx') {
        $this->importId = $importId;
        $this->filename = $filename;
        $this->fixedPassword = Hash::make('Cajamarca2025');

        // El resto del código de los catálogos está PERFECTO...
        $this->languages = collect(DB::table('languages')->pluck('id', 'name'))
            ->mapWithKeys(fn($id, $name) => [mb_strtoupper($name, 'UTF-8') => $id])->toArray();

        $this->ethnicities = collect(DB::table('ethnicities')->pluck('id', 'name'))
            ->mapWithKeys(fn($id, $name) => [mb_strtoupper($name, 'UTF-8') => $id])->toArray();

        $this->ubigeos = DB::table('ubigeos')->pluck('id', 'code')->toArray();

        $this->maritalStatuses = collect(DB::table('marital_statuses')->pluck('id', 'name'))
            ->mapWithKeys(fn($id, $name) => [mb_strtoupper($name, 'UTF-8') => $id])->toArray();

        $this->disabilityTypes = collect(DB::table('disability_types')->pluck('id', 'name'))
            ->mapWithKeys(fn($id, $name) => [mb_strtoupper($name, 'UTF-8') => $id])->toArray();
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            $this->reporte['errores'][] = "El archivo Excel parece estar vacío.";
            return;
        }

        foreach ($rows as $index => $row) {
            $filaNum = $index + 2;

            // Busca el DNI (Asegúrate que el nombre en el Excel sea igual a esto en minúsculas y sin espacios)
            $dni = trim($row['num_documento'] ?? $row['numero_documento'] ?? $row['dni'] ?? '');


            if (empty($dni)) {
                $this->recordRowResult($row->toArray(), 'OMITIDO', 'Fila sin DNI'); // AGREGA ESTA LÍNEA
                continue;
            }

            try {
                $status = ''; // AGREGA ESTA LÍNEA
                $msg = '';    // AGREGA ESTA LÍNEA
                DB::transaction(function () use ($row, $dni, &$status, &$msg) { // AGREGA &$status y &$msg
                    // 1. USUARIO
                    $user = User::firstOrCreate(
                        ['username' => $dni],
                        ['password' => $this->fixedPassword, 'is_active' => true, 'must_change_password' => true]
                    );
                    $user->assignRole('estudiante');

                    // 2. ACADÉMICO (Carrera y Plan)
                    $rawProg = $row['programa_carrera'] ?? $row['programa_de_estudios_carrera_profesional'] ?? '';
                    $infoProg = $this->extraerInfoPrograma($rawProg);
                    $program = StudyProgram::firstOrCreate(['name' => $infoProg['programa']]);
                    $plan = StudyPlan::firstOrCreate(
                        ['study_program_id' => $program->id, 'resolution_code' => $infoProg['resolucion']],
                        ['name' => 'Plan ' . $infoProg['resolucion'], 'evaluation_type' => 'competency', 'valid_from_year' => 2019]
                    );

                    // 3. PERSONA
                    $person = Person::updateOrCreate(
                        ['dni' => $dni],
                        [
                            'user_id' => $user->id,
                            'identity_document_type_id' => 1,
                            'names' => mb_strtoupper(trim($row['nombres'] ?? ''), 'UTF-8'),
                            'last_name_p' => mb_strtoupper(trim($row['apellido_paterno'] ?? ''), 'UTF-8'),
                            'last_name_m' => mb_strtoupper(trim($row['apellido_materno'] ?? ''), 'UTF-8'),
                            'gender' => (str_contains(strtoupper($row['sexo'] ?? ''), 'F')) ? 'F' : 'M',
                            'birth_date' => $this->limpiarFecha($row['fecha_nac'] ?? $row['fecha_nacimiento'] ?? null),
                            'nationality' => strtoupper($row['nacionalidad'] ?? 'PERUANA'),
                            'address' => $row['direccion_domicilio'] ?? $row['direccion_actual'] ?? null,
                            'personal_email' => $row['correo_electronico'] ?? null,
                            'phone' => $row['celular'] ?? $row['telefono_movil'] ?? null,
                            'ubigeo_birth_id' => $this->ubigeos[trim($row['ubigeo_nacimiento'] ?? '')] ?? null,
                            'ubigeo_residence_id' => $this->ubigeos[trim($row['ubigeo_domicilio'] ?? '')] ?? null,
                            'native_language_id' => $this->findId($this->languages, $row['lengua_materna'] ?? $row['lengua'] ?? ''),
                            'ethnicity_id' => $this->findId($this->ethnicities, $row['autoidentifacion_etnica'] ?? ''),
                            'marital_status_id' => $this->findId($this->maritalStatuses, $row['estado_civil'] ?? ''),
                            'has_disability' => $this->convertirSiNo($row['discapacidad'] ?? 'NO'),
                            'disability_type_id' => $this->findId($this->disabilityTypes, $row['tipo_discapacidad'] ?? ''),
                        ]
                    );

                    // 4. MATRÍCULA 2026-I
                    $period = AcademicPeriod::firstOrCreate(
                        ['name' => $row['periodo'] ?? '2026-I'],
                        ['start_date' => '2026-03-01', 'end_date' => '2026-07-31', 'status' => 'open']
                    );

                    $turno = Shift::firstOrCreate(['name' => strtoupper(trim($row['turno'] ?? 'MAÑANA'))]);

                    Enrollment::updateOrCreate(
                        ['person_id' => $person->id, 'academic_period_id' => $period->id],
                        [
                            'study_plan_id' => $plan->id,
                            'cycle' => $this->traducirCicloANumero($row['ciclo'] ?? '1'),
                            'enrollment_type_id' => 1,
                            'shift_id' => $turno->id,
                            'section_label' => strtoupper(substr($row['seccion'] ?? 'A', 0, 1)),
                        ]
                    );

                    // 5. FICHA SOCIOECONÓMICA
                    SocioeconomicFile::updateOrCreate(
                        ['person_id' => $person->id, 'academic_period_id' => $period->id],
                        [
                            'has_children' => $this->convertirSiNo($row['con_hijos'] ?? 'NO'),
                            'children_count' => is_numeric($row['cantidad_hijos'] ?? '') ? $row['cantidad_hijos'] : 0,
                            'is_validated' => true,
                            'scholarship_type_id' => 15, // Ajustar ID por defecto si es necesario
                        ]
                    );

                    if ($person->wasRecentlyCreated) {
                        $status = 'CREADO';
                        $msg = 'Alumno y matrícula 2026-I creados.';
                    } else {
                        $status = 'ACTUALIZADO';
                        $msg = 'Datos actualizados y matrícula renovada.';
                    }
                }); // Cierre de la transacción
                    $this->recordRowResult($row->toArray(), $status, $msg); // AGREGA ESTA LÍNEA
                } catch (\Exception $e) {
                    $this->recordRowResult($row->toArray(), 'ERROR', $e->getMessage());
            }
        }
        $this->saveImportHistory($this->filename, 'active_students');
    }

    public function getReporte() {
        return [
            'creados' => $this->c_created,
            'actualizados' => $this->c_updated,
            'omitidos' => $this->c_omitted,
            'errores_count' => $this->c_errors, // <--- Verifica que sea c_errors
        ];
    }

    private function findId($catalog, $value) {
        $val = mb_strtoupper(trim($value), 'UTF-8');
        if (empty($val) || $val === 'NO' || $val === '-') return null;
        return $catalog[$val] ?? null;
    }

    private function limpiarFecha($valor) {
        if (empty($valor)) return '2000-01-01';
        try {
            if (is_numeric($valor)) return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor)->format('Y-m-d');
            return Carbon::parse(str_replace('/', '-', $valor))->format('Y-m-d');
        } catch (\Exception $e) { return '2000-01-01'; }
    }

    public function registerEvents(): array {
        return [
            AfterImport::class => function($event) {
                Cache::put("import_active_progress_{$this->importId}", 'COMPLETO', 600);
            },
        ];
    }
}
