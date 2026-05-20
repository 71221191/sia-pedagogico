<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use App\Models\AcademicUnit;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseContentController extends Controller
{
    /**
     * Muestra el contenido completo de un curso para el alumno.
     */
    public function show(CourseSection $section)
    {
        $person = Auth::user()->person;

        // 1. Cargar las unidades con sus recursos y tareas
        // Importante: Filtramos los recursos que el profe marcó como 'is_visible'
        $units = AcademicUnit::where('course_section_id', $section->id)
            ->with([
                'resources' => function($q) {
                    $q->where('is_visible', true)->orderBy('created_at', 'asc');
                },
                'tasks' => function($q) {
                    $q->orderBy('due_date', 'asc');
                },
                'forums' => function($q) {
                    $q->where('is_active', true)->withCount('posts');
                }
            ])
            ->orderBy('order', 'asc')
            ->get();

        // 2. Por cada tarea, buscamos si el alumno ya hizo una entrega
        // Esto es para mostrarle su nota o el estado "Enviado" en la vista
        foreach ($units as $unit) {
            foreach ($unit->tasks as $task) {
                $task->my_submission = $task->submissions()
                    ->where('person_id', $person->id)
                    ->first();
            }
        }

        return Inertia::render('Student/Courses/Show', [
            'section' => $section->load('course', 'teacher'),
            'units' => $units
        ]);
    }
}
