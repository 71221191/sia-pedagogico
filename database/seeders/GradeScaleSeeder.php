<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeScaleSeeder extends Seeder
{
    public function run(): void
    {
        // Las 5 escalas oficiales del MINEDU
        $scales = [
            ['id' => 1, 'name' => 'PREVIO AL INICIO', 'numeric_equivalent' => 1],
            ['id' => 2, 'name' => 'INICIO', 'numeric_equivalent' => 2],
            ['id' => 3, 'name' => 'EN PROCESO', 'numeric_equivalent' => 3],
            ['id' => 4, 'name' => 'LOGRADO', 'numeric_equivalent' => 4],
            ['id' => 5, 'name' => 'DESTACADO', 'numeric_equivalent' => 5],
        ];

        foreach ($scales as $scale) {
            // Nota: En un sistema real, el study_plan_id se asignaría dinámicamente,
            // pero para que funcione ya mismo, las creamos de forma general.
            DB::table('grade_scales')->updateOrInsert(['id' => $scale['id']], $scale);
        }
        foreach ($scales as $scale) {
            // Ya no incluimos study_plan_id, así quedan como escalas maestras
            DB::table('grade_scales')->updateOrInsert(['id' => $scale['id']], $scale);
        }

        $this->command->info('✅ Escalas de notas globales cargadas.');
    }
}
