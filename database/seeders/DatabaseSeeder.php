<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,              // 1. Crea los roles (admin, estudiante, etc.)
            CatalogSeeder::class,           // 2. Crea al Admin real y los catálogos (DNI, Lenguas)
            PaymentConceptSeeder::class,    // 3. Carga los precios del TUPA
            OfficialCompetencySeeder::class,// 4. Carga las 12 competencias nacionales
            GradeScaleSeeder::class,        // 5. Carga los niveles (Logrado, Proceso)
            TimeSlotSeeder::class,          // 6. Carga las horas de clase
        ]);
    }
}
