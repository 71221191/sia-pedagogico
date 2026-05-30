<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SyllabusController extends Controller
{
    /**
     * Ver la pantalla del sílabo de la sección
     */
    public function index(CourseSection $section)
    {
        $section->load('course');

        return Inertia::render('Teacher/Syllabus/Index', [
            'section' => $section
        ]);
    }

    /**
     * Guardar o reemplazar el sílabo de la sección
     */
    public function store(Request $request, CourseSection $section)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf|max:2048', // Solo PDF, max 2MB
        ], [
            'file.mimes' => 'Solo se permiten archivos en formato PDF.',
            'file.max' => 'El archivo no debe pesar más de 2MB.'
        ]);

        // REGLA CLAVE: Si ya existía un sílabo anterior, borramos el PDF viejo físicamente para no acumular basura
        if ($section->syllabus_path) {
            Storage::disk('public')->delete($section->syllabus_path);
        }

        // Guardar el nuevo PDF en storage/app/public/syllabi
        $path = $request->file('file')->store('syllabi', 'public');

        // Actualizamos la sección directamente
        $section->update([
            'syllabus_path' => $path,
            'syllabus_name' => $request->name,
        ]);

        return back()->with('success', 'Sílabo subido y actualizado correctamente.');
    }

    /**
     * Eliminar el sílabo de la sección
     */
    public function destroy(CourseSection $section)
    {
        if ($section->syllabus_path) {
            Storage::disk('public')->delete($section->syllabus_path);
        }

        $section->update([
            'syllabus_path' => null,
            'syllabus_name' => null,
        ]);

        return back()->with('success', 'Sílabo eliminado correctamente.');
    }
}
