<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentConcept;

class PaymentConceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $concepts = [
            ['code' => '1.3.2 10.1 11', 'name' => 'Acceso a la información/copia simple formato A4', 'amount' => 0.10],
            ['code' => '1.3.2 10.1 11', 'name' => 'Acceso a la información/CD por unidad', 'amount' => 1.00],
            /* ['code' => '1.3.2 10.1 11', 'name' => 'Acceso a la información/CORREO', 'amount' => 0.00], */
            ['code' => '1.3.2 3.1 2', 'name' => 'Derechos Examen de Admisión', 'amount' => 250.00],
            ['code' => '1.3.2 3.1 3', 'name' => 'Derecho de sustentación: Incluye proyector multimedia, medalla, jurado y asesor (cuatro docentes)', 'amount' => 415.00],
            ['code' => '1.3.2 3.1 3', 'name' => 'Expedición de título', 'amount' => 39.00],
            ['code' => '1.3.2 3.1 4', 'name' => 'Certificado - estudios superiores (Pago por 10 ciclos)', 'amount' => 240.00],
            ['code' => '1.3.2 3.1 4', 'name' => 'Certificado - carreras técnicas (Pago por 6 ciclos)', 'amount' => 144.00],
            ['code' => '1.3.2 3.1 4', 'name' => 'Constancia de estudios', 'amount' => 20.00],
            ['code' => '1.3.2 3.1 4', 'name' => 'Constancia de egresado', 'amount' => 20.00],
            ['code' => '1.3.2 3.1 4', 'name' => 'Constancia del quinto superior', 'amount' => 20.00],
            ['code' => '1.3.2 3.1 4', 'name' => 'Constancia del tercio superior', 'amount' => 20.00],
            ['code' => '1.3.2 3.1 7', 'name' => 'Matrícula Ordinaria', 'amount' => 150.00],
            ['code' => '1.3.2 3.1 7', 'name' => 'Matrícula extemporanea 20% más de la matrícula', 'amount' => 180.00],
            ['code' => '1.3.2 3.1 7', 'name' => 'Reserva de matrícula', 'amount' => 20.00],
            ['code' => '1.3.2 3.1 7', 'name' => 'Licencia de estudios', 'amount' => 23.00],
            ['code' => '1.3.2 3.1 7', 'name' => 'Reingreso', 'amount' => 21.00],
            ['code' => '1.3.2 3.1 8', 'name' => 'Traslado Externo', 'amount' => 39.00],
            ['code' => '1.3.2 3.1 8', 'name' => 'Traslado Interno', 'amount' => 60.00],
            ['code' => '1.3.2 3.1 8', 'name' => 'Convalidaciones', 'amount' => 62.00],
            ['code' => '1.3.2 3.1 99', 'name' => 'Curso de subsanación matrícula ciclo - 0', 'amount' => 50.00],
            ['code' => '1.3.2 3.1 99', 'name' => 'Curso de subsanación por hora', 'amount' => 10.00],
            ['code' => '1.3.2 3.1 99', 'name' => 'Curso de subsanación por crédito', 'amount' => 20.00],
            [
                'id' => 99,
                'code' => 'LEGADO',
                'name' => 'PAGO MIGRADO (HISTORIAL SISTEMA ANTERIOR)',
                'amount' => 0.00, // El monto real se jalará del Excel
                'is_active' => false // Para que el alumno no pueda elegirlo en trámites nuevos
            ],
        ];

        foreach ($concepts as $c) {
            \App\Models\PaymentConcept::updateOrCreate(['name' => $c['name']], $c);
        }
    }
}
