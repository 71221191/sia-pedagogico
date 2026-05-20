<?php

namespace App\Imports;

use App\Models\{User, Person};
use Maatwebsite\Excel\Concerns\{ToCollection, WithHeadingRow};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Hash, DB};
use App\Traits\StandardizesAcademicData;
use App\Traits\TracksImportResults;

class TeachersImport implements ToCollection, WithHeadingRow
{
    use TracksImportResults;

    use StandardizesAcademicData;

    public $reporte = [
        'creados' => 0,
        'actualizados' => 0,
        'errores' => [],
    ];

    private $fixedPassword;

    private $filename; // Agregar esta propiedad

    public function __construct($filename = 'importacion_docentes.xlsx') {
        $this->fixedPassword = Hash::make('Docente2026*');
        $this->filename = $filename; // Guardar el nombre
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $dni = trim($row['dni'] ?? '');
            if (empty($dni)) {
                $this->recordRowResult($row->toArray(), 'OMITIDO', 'Fila sin DNI');
                continue;
            }

            try {
                $status = '';
                $msg = '';

                DB::transaction(function () use ($row, $dni, &$status, &$msg) {
                    $user = User::firstOrCreate(['username' => $dni], [
                        'password' => $this->fixedPassword,
                        'email' => $row['email'] ?? null,
                        'is_active' => true,
                        'must_change_password' => true
                    ]);

                    if (!$user->hasRole('docente')) $user->assignRole('docente');

                    $person = Person::updateOrCreate(['dni' => $dni], [
                        'user_id' => $user->id,
                        'identity_document_type_id' => 1,
                        'names' => mb_strtoupper(trim($row['nombres'] ?? ''), 'UTF-8'),
                        'last_name_p' => mb_strtoupper(trim($row['apellido_paterno'] ?? ''), 'UTF-8'),
                        'last_name_m' => mb_strtoupper(trim($row['apellido_materno'] ?? ''), 'UTF-8'),
                        'gender' => (str_contains(strtoupper($row['sexo'] ?? ''), 'F')) ? 'F' : 'M',
                        'birth_date' => $this->limpiarFechaDocente($row['fecha_nacimiento'] ?? null),
                        'personal_email' => $row['email'] ?? null,
                        'phone' => $row['celular'] ?? null,
                        'address' => $row['direccion'] ?? null,
                        'nationality' => 'PERUANA',
                    ]);

                    $status = $person->wasRecentlyCreated ? 'CREADO' : 'ACTUALIZADO';
                    $msg = $status === 'CREADO' ? 'Docente nuevo registrado.' : 'Datos de docente actualizados.';
                });

                // ESTO ES LO QUE TE FALTABA: Registrar el resultado en el Trait
                $this->recordRowResult($row->toArray(), $status, $msg);

            } catch (\Exception $e) {
                $this->recordRowResult($row->toArray(), 'ERROR', $e->getMessage());
            }
        }
        // ESTO TAMBIÉN FALTABA: Guardar en la tabla legacy_imports
        $this->saveImportHistory($this->filename, 'teachers');
    }

    private function limpiarFechaDocente($valor) {
        if (empty($valor)) return '1980-01-01';
        try {
            if (is_numeric($valor)) return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor)->format('Y-m-d');
            return \Carbon\Carbon::parse(str_replace('/', '-', $valor))->format('Y-m-d');
        } catch (\Exception $e) { return '1980-01-01'; }
    }

    public function getReporte() {
        return [
            'creados' => $this->c_created,
            'actualizados' => $this->c_updated,
            'omitidos' => $this->c_omitted,
            'errores_count' => $this->c_errors,
        ];
    }
}
