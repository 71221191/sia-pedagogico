<?php

namespace App\Services;

use App\Models\Person;
use App\Models\AcademicPeriod;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    public function getStudentDashboardData(Person $person)
    {
        $period = AcademicPeriod::where('status', 'open')->first();

        // 1. IDENTIDAD REAL (Desde la tabla people con sus nombres de catálogos)
        $identidad = DB::table('people')
            ->leftJoin('languages', 'people.native_language_id', '=', 'languages.id')
            ->leftJoin('ethnicities', 'people.ethnicity_id', '=', 'ethnicities.id')
            ->leftJoin('marital_statuses', 'people.marital_status_id', '=', 'marital_statuses.id')
            ->where('people.id', $person->id)
            ->select(
                'people.*',
                'languages.name as language_name',
                'ethnicities.name as ethnicity_name',
                'marital_statuses.name as marital_status_name'
            )
            ->first();

        // 2. MATRÍCULA (Carrera y Ciclo)
        $enrollment = $person->enrollments()
            ->join('academic_periods', 'enrollments.academic_period_id', '=', 'academic_periods.id')
            ->select('enrollments.*', 'academic_periods.start_date as period_start')
            ->orderBy('period_start', 'desc') // Primero por fecha del periodo
            ->orderBy('enrollments.id', 'desc') // Luego por el ID más alto
            ->with('studyPlan.studyProgram')
            ->first();

        // 3. BIENESTAR (Desde la última ficha)
        $ficha = DB::table('socioeconomic_files')
            ->leftJoin('scholarship_types', 'socioeconomic_files.scholarship_type_id', '=', 'scholarship_types.id')
            ->where('person_id', $person->id)
            ->select('socioeconomic_files.*', 'scholarship_types.name as scholarship_name')
            ->latest()
            ->first();


        // 4. RANKING
        $ranking = DB::table('academic_rankings')
            ->join('academic_periods', 'academic_rankings.academic_period_id', '=', 'academic_periods.id')
            ->where('academic_rankings.person_id', $person->id)
            ->select(
                'academic_rankings.position',
                'academic_rankings.total_students',
                'academic_rankings.weighted_average'
            )
            // CLAVE: Ordenamos por la fecha de inicio del periodo para que el más nuevo esté arriba
            ->orderBy('academic_periods.start_date', 'desc')
            // Por si se calculó dos veces el mismo día, tomamos el último ID
            ->orderBy('academic_rankings.id', 'desc')
            ->first(); // TOMAMOS SOLO EL MÁS RECIENTE

        return [
            'full_name' => "{$person->last_name_p} {$person->last_name_m}, {$person->names}",
            'dni' => $person->dni,

            'academic' => [
                'program' => $enrollment->studyPlan->studyProgram->name ?? 'Carrera no asignada',
                'cycle' => $enrollment->cycle ?? 'N/A',
                'scholarship' => $ficha->scholarship_name ?? 'NINGUNA',
                'has_scholarship' => ($ficha && $ficha->scholarship_type_id != 15),
            ],

            'bio' => [
                'civil_status' => $identidad->marital_status_name ?? 'No especificado',
                'language' => $identidad->language_name ?? 'No especificado',
                'ethnicity' => $identidad->ethnicity_name ?? 'No especificado',
                'has_children' => $ficha ? ($ficha->has_children ? 'SÍ' : 'NO') : 'NO',

                // --- CORRECCIÓN AQUÍ: Cambiamos is_working por works ---
                'is_working' => $ficha ? ($ficha->works ? 'SÍ' : 'NO') : 'NO',
            ],

            'contact' => [
                // 1. Correo Institucional: Viene SIEMPRE de la tabla de login (users)
                'institutional_email' => $person->user->email,

                // 2. Correo Personal: Viene SIEMPRE de la tabla de perfil (people)
                'personal_email'      => $person->personal_email ?? 'No registrado',

                // 3. Celular Formateado: 964 884 971
                'phone' => $person->phone
                        ? implode(' ', str_split($person->phone, 3))
                        : 'No registrado',

                'address'  => $person->address ?? 'No registrado',
                'locality' => $person->locality ?? 'Cajamarca',
            ],

            'merit' => $ranking ? [
                'position' => $ranking->position,
                'total' => $ranking->total_students,
                'average' => number_format($ranking->weighted_average, 2)
            ] : null,

            'photo' => $person->official_photo_path ? asset('storage/' . $person->official_photo_path) : null
        ];
    }
}
