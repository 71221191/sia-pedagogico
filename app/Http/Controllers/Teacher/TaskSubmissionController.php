<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskSubmissionController extends Controller
{
    /**
     * Lista todos los alumnos y sus entregas para una tarea específica.
     */
    public function index(Task $task)
    {
        $task->load('unit.section.course');

        // Traemos las entregas vinculadas con los datos del alumno (people)
        $submissions = $task->submissions()
            ->with('student:id,names,last_name_p,last_name_m')
            ->orderBy('submitted_at', 'desc')
            ->get();

        return Inertia::render('Teacher/Portfolio/Submissions', [
            'task' => $task,
            'submissions' => $submissions
        ]);
    }

    /**
     * Registra la calificación y el feedback del docente.
     */
    public function grade(Request $request, TaskSubmission $submission)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:20',
            'teacher_feedback' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'score' => $validated['score'],
            'teacher_feedback' => $validated['teacher_feedback'],
            'status' => 'graded' // Cambiamos el estado a calificado
        ]);

        return back()->with('success', 'Calificación registrada correctamente.');
    }
}
