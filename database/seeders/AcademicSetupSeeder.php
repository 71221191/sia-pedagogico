<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicSetupSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buscamos el ID del primer plan de estudios disponible
        $plan = DB::table('study_plans')->first();

        if (!$plan) {
            $this->command->error('No hay Planes de Estudio en la base de datos. Registre uno primero.');
            return;
        }

        $planId = $plan->id;

        // 2. LLENAR ESCALAS DE CALIFICACIÓN (Cualitativas 1-5)
        $scales = [
            ['id' => 1, 'name' => 'PREVIO AL INICIO', 'numeric_equivalent' => 1, 'study_plan_id' => $planId],
            ['id' => 2, 'name' => 'INICIO', 'numeric_equivalent' => 2, 'study_plan_id' => $planId],
            ['id' => 3, 'name' => 'EN PROCESO', 'numeric_equivalent' => 3, 'study_plan_id' => $planId],
            ['id' => 4, 'name' => 'LOGRADO', 'numeric_equivalent' => 4, 'study_plan_id' => $planId],
            ['id' => 5, 'name' => 'DESTACADO', 'numeric_equivalent' => 5, 'study_plan_id' => $planId],
        ];

        foreach ($scales as $scale) {
            // Usamos updateOrInsert para evitar duplicados si lo corres dos veces
            DB::table('grade_scales')->updateOrInsert(
                ['id' => $scale['id']],
                $scale
            );
        }

        $this->command->info('Escalas de notas cargadas correctamente para el Plan: ' . $plan->name);

        // 3. VINCULAR COMPETENCIAS AL CURSO 374 (Si existe)
        // Asegúrate de que el curso 374 exista en tu base de datos actual
        $cursoExiste = DB::table('courses')->where('id', 374)->exists();

        if ($cursoExiste) {
            $vinculos = [
                ['course_id' => 374, 'competency_id' => 1, 'weight' => 33.33],
                ['course_id' => 374, 'competency_id' => 2, 'weight' => 33.33],
                ['course_id' => 374, 'competency_id' => 3, 'weight' => 33.34],
            ];

            foreach ($vinculos as $v) {
                DB::table('course_competency')->updateOrInsert(
                    ['course_id' => $v['course_id'], 'competency_id' => $v['competency_id']],
                    $v
                );
            }
            $this->command->info('Competencias vinculadas al curso 374.');
        }
    }
}
