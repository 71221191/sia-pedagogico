<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateRankings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-rankings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando Sincronización Global de Cuadros de Mérito...');
        $service = new \App\Services\MeritService();

        // 1. Buscamos TODOS los periodos que tienen matrículas registradas
        $periodIds = \App\Models\Enrollment::distinct()
            ->pluck('academic_period_id');

        foreach ($periodIds as $id) {
            $period = \App\Models\AcademicPeriod::find($id);
            $this->info("Procesando Ranking para: {$period->name}...");

            // Llamamos al service para este periodo específico
            $service->updateAllRankings($id);
        }

        $this->info('¡Misión cumplida! Todo el historial académico ha sido rankeado.');
    }

}
