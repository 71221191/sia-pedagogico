<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicUnit;
use App\Models\LearningResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class LearningResourceController extends Controller
{
    /**
     * Lista los recursos de una unidad específica.
     */
    public function index(AcademicUnit $unit)
    {
        // Cargamos la unidad con su sección y curso para el título
        $unit->load('section.course');
        $resources = $unit->resources()->orderBy('created_at', 'desc')->get();

        return Inertia::render('Teacher/Portfolio/Resources', [
            'unit' => $unit,
            'resources' => $resources
        ]);
    }

    /**
     * Guarda un nuevo recurso (Archivo o Link).
     */
    public function store(Request $request, AcademicUnit $unit)
    {
        $request->validate([
            'type' => 'required|in:file,link',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'file' => 'required_if:type,file|nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,png|max:5120', // 5MB
            'url' => 'required_if:type,link|nullable|url',
        ]);

        $data = [
            'academic_unit_id' => $unit->id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'is_visible' => true,
        ];

        if ($request->type === 'file' && $request->hasFile('file')) {
            $path = $request->file('file')->store('resources/unit_' . $unit->id, 'public');
            $data['file_path'] = $path;
        } else {
            $data['url'] = $request->url;
        }

        LearningResource::create($data);

        return back()->with('success', 'Recurso agregado correctamente.');
    }

    /**
     * Alterna la visibilidad del recurso para el alumno.
     */
    public function toggleVisibility(LearningResource $resource)
    {
        $resource->update(['is_visible' => !$resource->is_visible]);
        return back();
    }

    /**
     * Elimina el recurso.
     */
    public function destroy(LearningResource $resource)
    {
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }
        $resource->delete();

        return back()->with('success', 'Recurso eliminado.');
    }
}
