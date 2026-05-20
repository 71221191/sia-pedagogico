<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicUnit;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Lista las tareas de una unidad.
     */
    public function index(AcademicUnit $unit)
    {
        $unit->load('section.course');
        // Traemos las tareas con el conteo de entregas realizadas por los alumnos
        $tasks = $unit->tasks()->withCount('submissions')->orderBy('due_date', 'asc')->get();

        return Inertia::render('Teacher/Portfolio/Tasks', [
            'unit' => $unit,
            'tasks' => $tasks
        ]);
    }

    /**
     * Guarda una nueva tarea con restricciones pro.
     */
    public function store(Request $request, AcademicUnit $unit)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'due_date' => 'required|date|after:now',
            'closing_date' => 'required|date|after:due_date',
            'max_score' => 'required|numeric|min:0|max:20',
            'allowed_formats' => 'required|string', // ej: "pdf,docx"
            'max_file_size_kb' => 'required|integer|min:1024|max:10240', // 1MB a 10MB
        ]);

        $unit->tasks()->create($validated);

        return back()->with('success', 'Tarea publicada correctamente.');
    }

    /**
     * Elimina la tarea (Solo si no tiene entregas para evitar pérdida de notas).
     */
    public function destroy(Task $task)
    {
        if ($task->submissions()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una tarea que ya tiene entregas de alumnos.');
        }

        $task->delete();
        return back()->with('success', 'Tarea eliminada.');
    }
}
