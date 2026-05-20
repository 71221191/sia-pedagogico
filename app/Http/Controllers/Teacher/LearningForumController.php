<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicUnit;
use App\Models\LearningForum;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LearningForumController extends Controller
{
    public function index(AcademicUnit $unit)
    {
        $unit->load('section.course');
        $forums = $unit->forums()->withCount('posts')->get();

        return Inertia::render('Teacher/Portfolio/Forums', [
            'unit' => $unit,
            'forums' => $forums
        ]);
    }

    public function store(Request $request, AcademicUnit $unit)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
        ]);

        $unit->forums()->create($validated);

        return back()->with('success', 'Foro de debate creado correctamente.');
    }

    public function toggle(LearningForum $forum)
    {
        $forum->update(['is_active' => !$forum->is_active]);
        return back()->with('success', 'El estado del debate ha sido actualizado.');
    }

    public function destroy(LearningForum $forum)
    {
        $forum->delete();
        return back()->with('success', 'El foro y sus mensajes han sido eliminados correctamente.');
    }
}
