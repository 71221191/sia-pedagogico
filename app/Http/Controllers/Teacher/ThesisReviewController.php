<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ThesisProject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ThesisService;
use App\Models\ThesisJuror;

class ThesisReviewController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->person;

        $projects = ThesisProject::with(['authors', 'documents', 'jurors.teacher', 'defenseAct'])
            ->where('advisor_id', $teacher->id)
            ->orWhereHas('jurors', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->get();

        return Inertia::render('Teacher/Thesis/ReviewList', [
            'projects' => $projects
        ]);
    }

    public function updateScore(Request $request, ThesisProject $project)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:20'
        ]);

        // 1. Obtenemos el ID del humano (Person)
        $teacherId = Auth::user()->person->id ?? null;

        // --- DEBUG: Vamos a ver qué está buscando Laravel ---
        // \Log::info("Intentando actualizar: Proyecto {$project->id}, Profesor {$teacherId}, Nota {$request->score}");

        // 2. Ejecutamos la actualización y guardamos el resultado en una variable
        $affected = \App\Models\ThesisJuror::where('thesis_project_id', $project->id)
            ->where('teacher_id', $teacherId)
            ->update(['score' => $request->score]);

        // --- LA PRUEBA DE FUEGO ---
        if ($affected === 0) {
            // Si entra aquí, es porque NO encontró al profesor en la tabla de jurados
            return back()->with('error', "Error: No se encontró tu registro como jurado en este proyecto (ID Busca: $teacherId).");
        }

        // 3. Si hubo cambios, sincronizamos el promedio final
        $service = new \App\Services\ThesisService();
        $service->syncFinalScore($project);

        return back()->with('success', 'Calificación guardada y promedio actualizado.');
    }

}
