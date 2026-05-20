<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TaskSubmissionController extends Controller
{
    /**
     * Muestra el detalle de la tarea y el formulario de entrega.
     */
    public function show(Task $task)
    {
        $person = Auth::user()->person;
        $task->load('unit.section.course');

        // Buscamos si ya existe una entrega
        $submission = TaskSubmission::where('task_id', $task->id)
            ->where('person_id', $person->id)
            ->first();

        return Inertia::render('Student/Tasks/Show', [
            'task' => $task,
            'submission' => $submission
        ]);
    }

    /**
     * Procesa la subida del archivo de la tarea.
     */
    public function store(Request $request, Task $task)
    {
        $person = Auth::user()->person;
        $now = now();

        // 1. Validar si el candado ya se cerró (Closing Date)
        if ($now->greaterThan($task->closing_date)) {
            return back()->with('error', 'El plazo de entrega ha expirado definitivamente.');
        }

        // 2. Validar archivo (Peso y Formato dinámico según la tarea)
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:' . $task->allowed_formats,
                'max:' . $task->max_file_size_kb
            ]
        ], [
            'file.mimes' => "Solo se permiten los formatos: {$task->allowed_formats}",
            'file.max' => "El archivo excede el peso máximo permitido (" . ($task->max_file_size_kb / 1024) . "MB)."
        ]);

        // 3. Determinar estado (Si es después de due_date es 'late')
        $status = $now->greaterThan($task->due_date) ? 'late' : 'sent';

        // 4. Guardar archivo físicamente
        $path = $request->file('file')->store('submissions/task_' . $task->id, 'public');

        // 5. Registrar en la base de datos (Si ya existía, se actualiza/reemplaza)
        TaskSubmission::updateOrCreate(
            ['task_id' => $task->id, 'person_id' => $person->id],
            [
                'file_path' => $path,
                'submitted_at' => $now,
                'status' => $status,
                'teacher_feedback' => null // Se limpia si re-entrega
            ]
        );

        return back()->with('success', 'Tu tarea ha sido enviada correctamente.');
    }
}
