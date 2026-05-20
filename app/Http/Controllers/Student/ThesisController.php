<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ThesisProject;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ThesisController extends Controller
{
    public function index()
    {
        $person = Auth::user()->person;

        // Buscamos si el alumno ya pertenece a algún proyecto
        $myProject = $person->thesisProjects()
            ->with([
                'authors',
                'advisor',
                'jurors.teacher',
                'defenseAct',
                'documents'
            ])
            ->first();

        return Inertia::render('Student/Thesis/Index', [
            // Si no hay proyecto, mandamos null, pero el frontend
            // debe tener un v-if="project" para mostrar el formulario de registro
            'project' => $myProject ? $myProject : null
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $person = $user->person;

        // 1. Identificamos el periodo activo
        $period = \App\Models\AcademicPeriod::where('status', 'open')->first();

        if (!$period) {
            return back()->with('error', 'No hay un proceso académico activo.');
        }

        // 2. Buscamos la matrícula actual de Anderson para saber sus "coordenadas"
        $myEnrollment = \App\Models\Enrollment::where('person_id', $person->id)
            ->where('academic_period_id', $period->id)
            ->first();

        if (!$myEnrollment) {
            return back()->with('error', 'Debes estar matriculado para registrar un proyecto.');
        }

        // 3. BUSCADOR ESTRICTO: Solo alumnos en su mismo Periodo, Plan, Ciclo y Sección
        $students = \App\Models\Person::whereHas('enrollments', function($q) use ($myEnrollment, $period) {
                $q->where('academic_period_id', $period->id)
                ->where('study_plan_id', $myEnrollment->study_plan_id)
                ->where('cycle', $myEnrollment->cycle)
                ->where('section_label', $myEnrollment->section_label);
            })
            ->where('id', '!=', $person->id) // Excluirse a sí mismo
            ->get(['id', 'names', 'last_name_p', 'last_name_m'])
            ->map(function($s) {
                return [
                    'id' => $s->id,
                    'label' => "{$s->last_name_p} {$s->names}" // Formato limpio para el select
                ];
            });

        return Inertia::render('Student/Thesis/Create', [
            'students' => $students
        ]);
    }

    public function store(Request $request)
    {
        $person = Auth::user()->person;

        $validated = $request->validate([
            'title' => 'required|string|max:500|unique:thesis_projects,title',
            'research_line' => 'required|string',
            'partner_id' => 'nullable|exists:people,id', // ID del compañero si es en par
        ]);

        return DB::transaction(function () use ($validated, $person) {
            // 1. Crear el proyecto
            $project = ThesisProject::create([
                'title' => $validated['title'],
                'research_line' => $validated['research_line'],
                'status' => 'registered', // Estado inicial
            ]);

            // 2. Vincular al autor principal (el que está logueado)
            $project->authors()->attach($person->id);

            // 3. Vincular al compañero (si existe)
            if ($validated['partner_id']) {
                $project->authors()->attach($validated['partner_id']);
            }

            return redirect()->route('student.thesis.index')
                ->with('success', 'Proyecto de tesis registrado correctamente. Espere la validación de Secretaría.');
        });
    }

    public function uploadDocument(Request $request, ThesisProject $project)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:project,report,final_draft',
            'file' => 'required|file|mimes:pdf|max:5120', // Max 5MB
        ], [
            'file.mimes' => 'El documento debe ser un archivo PDF.',
            'file.max' => 'El archivo no debe pesar más de 5MB.'
        ]);

        // 1. Guardar el archivo físicamente
        // Se guardará en: storage/app/public/thesis_docs/{id_proyecto}/archivo.pdf
        $path = $request->file('file')->store('thesis_docs/' . $project->id, 'public');

        // 2. Registrar en la base de datos
        $project->documents()->create([
            'name' => $request->name,
            'type' => $request->type,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Documento subido correctamente.');
    }
}
