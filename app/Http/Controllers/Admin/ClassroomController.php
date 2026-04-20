<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassroomController extends Controller
{
    public function index()
    {
        // Traemos todas las aulas ordenadas por tipo y nombre
        $classrooms = Classroom::orderBy('type')->orderBy('name')->get();

        return Inertia::render('Admin/Classrooms/Index', [
            'classrooms' => $classrooms
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:classrooms,name',
            'type' => 'required|in:aula,laboratorio,taller,otros',
            'capacity' => 'required|integer|min:1|max:100',
        ]);

        Classroom::create($validated);

        return back()->with('success', 'Ambiente registrado correctamente.');
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:classrooms,name,' . $classroom->id,
            'type' => 'required|in:aula,laboratorio,taller,otros',
            'capacity' => 'required|integer|min:1|max:100',
        ]);

        $classroom->update($validated);

        return back()->with('success', 'Ambiente actualizado.');
    }

    public function destroy(Classroom $classroom)
    {
        // Por ahora eliminamos directo, luego pondremos validación si ya tiene horarios
        $classroom->delete();

        return back()->with('success', 'Ambiente eliminado.');
    }
}
