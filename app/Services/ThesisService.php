<?php

namespace App\Services;

use App\Models\Person;
use App\Models\ThesisProject;
use Illuminate\Support\Facades\DB;
use App\Models\ThesisJuror;
use App\Models\DefenseAct;

class ThesisService
{
    public function syncProjectsFromPeople()
    {
        return DB::transaction(function () {
            // Buscamos personas que tienen proyecto aprobado en la tabla people
            $peopleWithProjects = Person::where('has_approved_project', 1)
                ->whereNotNull('project_name')
                ->get();

            $count = 0;

            foreach ($peopleWithProjects as $person) {
                $cleanTitle = trim(mb_strtoupper($person->project_name));

                // Buscamos o creamos el expediente oficial
                $project = ThesisProject::firstOrCreate(
                    ['title' => $cleanTitle],
                    [
                        'research_line' => 'Importado del Sistema Anterior',
                        'status' => 'approved',
                        'is_imported' => true
                    ]
                );

                // Vinculamos al alumno (y a su compañero si tienen el mismo título)
                $project->authors()->syncWithoutDetaching([$person->id]);

                if ($project->wasRecentlyCreated) $count++;
            }

            return $count;
        });
    }

    public function updateJurorScore($projectId, $teacherId, $score)
    {
        // 1. Actualizamos la nota del jurado específico
        \App\Models\ThesisJuror::where('thesis_project_id', $projectId)
            ->where('teacher_id', $teacherId)
            ->update(['score' => $score]);

        // 2. Recalculamos el promedio de los 3 jurados
        $jurors = \App\Models\ThesisJuror::where('thesis_project_id', $projectId)->get();

        // Solo calculamos si los 3 ya tienen nota (opcional, según regla del instituto)
        $average = $jurors->avg('score');

        // 3. Actualizamos el Acta de Sustentación final
        $result = ($average >= 14) ? 'aprobado' : 'desaprobado';

        \App\Models\DefenseAct::updateOrCreate(
            ['thesis_project_id' => $projectId],
            [
                'score' => $average,
                'result' => $result,
                // También guardamos las notas individuales en el acta para los reportes PDF
                'score_president' => $jurors->where('role', 'presidente')->first()->score ?? 0,
                'score_secretary' => $jurors->where('role', 'secretario')->first()->score ?? 0,
                'score_vocal' => $jurors->where('role', 'vocal')->first()->score ?? 0,
            ]
        );
    }

    public function syncFinalScore(ThesisProject $project)
    {
        $jurors = $project->jurors;
        $average = $jurors->avg('score');

        if ($average === null) return;

        $result = ($average >= 14) ? 'aprobado' : 'desaprobado';

        // BUSCAMOS O CREAMOS EL ACTA
        $project->defenseAct()->updateOrCreate(
            ['thesis_project_id' => $project->id],
            [
                'score' => $average,
                'result' => $result,
                'score_president' => $jurors->where('role', 'presidente')->first()->score ?? 0,
                'score_secretary' => $jurors->where('role', 'secretario')->first()->score ?? 0,
                'score_vocal' => $jurors->where('role', 'vocal')->first()->score ?? 0,

                // --- ESTA ES LA SOLUCIÓN ---
                // Si el acta es nueva, jalamos la fecha/hora que ya programó la secretaria
                'defense_date' => $project->defenseAct->defense_date ?? $project->scheduled_date ?? now(),
                'defense_time' => $project->defenseAct->defense_time ?? $project->scheduled_time ?? now()->format('H:i'),
                'modality'     => $project->defenseAct->modality ?? 'presencial',
            ]
        );

        if ($result === 'aprobado' && $project->status !== 'defended') {
            $project->update(['status' => 'defended']);
        }
    }

}
