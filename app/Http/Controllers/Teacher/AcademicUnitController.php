<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use App\Models\AcademicUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AcademicUnitController extends Controller
{
    /**
     * Muestra la vista para configurar las unidades de una sección.
     */
    public function index(CourseSection $section)
    {
        $section->load('course');

        // Traemos las unidades actuales si ya existen
        $units = $section->academicUnits;

        return Inertia::render('Teacher/Portfolio/UnitsConfig', [
            'section' => $section,
            'units' => $units
        ]);
    }

    /**
     * Crea las unidades de forma masiva (2, 3 o 4 unidades).
     */
    public function storeBatch(Request $request, CourseSection $section)
    {
        $request->validate([
            'number_of_units' => 'required|integer|min:1|max:6',
        ]);

        return DB::transaction(function () use ($request, $section) {

            // 1. Si ya tiene unidades, no permitimos sobreescribir para no borrar datos
            if ($section->academicUnits()->count() > 0) {
                return back()->with('error', 'Este curso ya tiene unidades configuradas.');
            }

            $total = $request->number_of_units;

            // 2. Creamos las unidades en bucle
            for ($i = 1; $i <= $total; $i++) {
                AcademicUnit::create([
                    'course_section_id' => $section->id,
                    'name' => "UNIDAD DIDÁCTICA " . $this->toRoman($i),
                    'order' => $i,
                ]);
            }

            return back()->with('success', "Se han configurado {$total} unidades correctamente.");
        });
    }

    public function addOneUnit(CourseSection $section)
    {
        // 1. Buscamos el orden de la última unidad creada
        $lastOrder = $section->academicUnits()->max('order') ?? 0;

        // Ponemos un límite razonable (ejemplo 10) para que no rompan el diseño
        if ($lastOrder >= 10) {
            return back()->with('error', 'Has alcanzado el límite máximo de unidades.');
        }

        $nextOrder = $lastOrder + 1;

        // 2. Creamos la nueva unidad siguiendo el correlativo
        AcademicUnit::create([
            'course_section_id' => $section->id,
            'name' => "UNIDAD DIDÁCTICA " . $this->toRoman($nextOrder),
            'order' => $nextOrder,
        ]);

        return back()->with('success', 'Nueva unidad añadida al curso.');
    }

    /**
     * Helper simple para nombres romanos
     */
    private function toRoman($number)
    {
        $map = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X'];
        return $map[$number] ?? $number;
    }

    // 2. NUEVO: Método para actualizar el nombre
    public function update(Request $request, AcademicUnit $unit)
    {
        $validated = $request->validate(['name' => 'required|string|max:100']);
        $unit->update($validated);
        return back()->with('success', 'Nombre de la unidad actualizado.');
    }

    // 3. NUEVO: Método para eliminar con seguridad
    public function destroy(AcademicUnit $unit)
    {
        // 1. Contamos qué tiene adentro
        $tareas = $unit->tasks()->count();
        $recursos = $unit->resources()->count();
        $foros = $unit->forums()->count();

        // 2. Si tiene algo, mandamos un mensaje detallado
        if ($tareas > 0 || $recursos > 0 || $foros > 0) {
            $detalle = [];
            if ($tareas > 0) $detalle[] = "$tareas tareas";
            if ($recursos > 0) $detalle[] = "$recursos materiales";
            if ($foros > 0) $detalle[] = "$foros foros";

            return back()->with('error', 'No se puede eliminar. Esta unidad aún contiene: ' . implode(', ', $detalle) . '.');
        }

        $unit->delete();
        return back()->with('success', 'La unidad ha sido eliminada del sistema.');
    }



}
