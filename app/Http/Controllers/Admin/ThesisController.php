<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThesisProject;
use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ThesisController extends Controller
{
    public function index(Request $request)
    {
        $projects = ThesisProject::with(['authors', 'advisor'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhereHas('authors', function ($q) use ($search) {
                        $q->where('last_name_p', 'like', "%{$search}%")
                            ->orWhere('names', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString(); // IMPORTANTE: Mantiene el texto de búsqueda al cambiar de página

        return Inertia::render('Admin/Thesis/Index', [
            'projects' => $projects,
            'filters' => $request->only(['search'])
        ]);
    }

    public function show(ThesisProject $project)
    {
        // CARGA PROFUNDA (Eager Loading):
        // Entramos desde autores -> hasta su plan de estudios
        $project->load([
            // Añadimos 'academicPeriod' al final de esta cadena:
            'authors.enrollments.studyPlan',
            'authors.enrollments.academicPeriod', // <--- ESTA ES LA LÍNEA QUE FALTA
            'advisor',
            'jurors.teacher',
            'documents',
            'defenseAct'
        ]);

        // ... el resto del código de los profesores
        $teachers = Person::whereHas('user.roles', fn($q) => $q->where('name', 'docente'))
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'full_name' => "{$t->last_name_p} {$t->last_name_m}, {$t->names}"
                ];
            });

        return Inertia::render('Admin/Thesis/Show', [
            'project' => $project,
            'teachers' => $teachers
        ]);
    }

    public function assignAdvisor(Request $request, ThesisProject $project)
    {
        $validated = $request->validate([
            'advisor_id' => 'required|exists:people,id',
        ]);

        $project->update([
            'advisor_id' => $validated['advisor_id'],
            'status' => 'approved' // Al asignar asesor, el proyecto queda aprobado para iniciar
        ]);

        return back()->with('success', 'Asesor asignado correctamente.');
    }
    public function assignJurors(Request $request, ThesisProject $project)
    {
        $validated = $request->validate([
            'jurors' => 'required|array|size:3',
            'jurors.*.teacher_id' => 'required|exists:people,id',
            'jurors.*.role' => 'required|in:presidente,secretario,vocal',
        ]);

        // 1. Validar duplicados y asesores (La lógica que ya teníamos)
        $jurorIds = collect($validated['jurors'])->pluck('teacher_id')->toArray();
        if (count(array_unique($jurorIds)) < 3 || in_array($project->advisor_id, $jurorIds)) {
            return back()->withErrors(['jurors' => 'Conflicto de jurados o nombres repetidos.']);
        }

        // 2. AUTOMATIZACIÓN: Asignar número de oficio correlativo si no tiene uno
        if (!$project->office_number) {
        $lastNumber = \App\Models\ThesisProject::whereYear('created_at', date('Y'))->max('office_number');
        $project->office_number = ($lastNumber ?? 0) + 1;
        }

        // 2. ESTA ES LA LÍNEA QUE FALTA: Guardar el texto para el PDF
        // Esto guardará "001-2026" en la columna que lee el reporte
        $project->document_correlative = str_pad($project->office_number, 3, '0', STR_PAD_LEFT) . '-' . date('Y');

        // 3. Jalar datos automáticos
        $project->specialty_resolution = $project->auto_resolution;
        $project->promotion_year = $project->auto_promotion;
        $project->status = ThesisProject::STATUS_ASSIGNED;

        $project->save();

        // 4. Guardar jurados (lo que ya tenías)
        $project->jurors()->delete();
        foreach ($validated['jurors'] as $jurorData) {
            $project->jurors()->create($jurorData);
        }

        return back()->with('success', 'Jurados oficializados. Correlativo: ' . $project->document_correlative);
    }

    // Para programar la cita sin calificar
    public function scheduleDefense(Request $request, ThesisProject $project)
    {
        $validated = $request->validate([
            'scheduled_date' => 'required|date|after:today',
            'scheduled_time' => 'required',
            'scheduled_location' => 'required|string|max:100',
        ]);

        $project->update(array_merge($validated, [
            'status' => ThesisProject::STATUS_SCHEDULED
        ]));

        return back()->with('success', 'Sustentación programada correctamente.');
    }

    public function recordDefense(Request $request, ThesisProject $project)
    {
        // 1. EL CANDADO VA PRIMERO (Seguridad de Servidor)
        // Si ya está defendido o cerrado, rebotamos la petición de inmediato
        if ($project->status === 'defended' || $project->status === 'closed') {
            return back()->with('error', 'Acceso denegado: Este expediente ya está oficializado e inmutable.');
        }

        // 2. LA VALIDACIÓN
        $validated = $request->validate([
            'defense_date' => 'required|date',
            'defense_time' => 'required',
            'modality' => 'required|in:presencial,virtual',
            'score_president' => 'required|numeric|min:0|max:20',
            'score_secretary' => 'required|numeric|min:0|max:20',
            'score_vocal' => 'required|numeric|min:0|max:20',
        ]);

        // 3. EL CÁLCULO
        $average = ($validated['score_president'] + $validated['score_secretary'] + $validated['score_vocal']) / 3;

        // REGLA CRÍTICA: Mínimo 14.00 para aprobar.
        $result = ($average >= 14) ? 'aprobado' : 'desaprobado';

        // 4. LA PERSISTENCIA (Guardar en DB)
        $project->defenseAct()->updateOrCreate(
            ['thesis_project_id' => $project->id],
            array_merge($validated, [
                'score' => $average,
                'result' => $result
            ])
        );

        // 5. CAMBIO DE ESTADO
        if ($result === 'aprobado') {
            $project->update(['status' => 'defended']);
        }

        // 6. EL ÚNICO RETURN AL FINAL
        return back()->with('success', 'Resultado registrado. Promedio: ' . number_format($average, 2));
    }

    public function updateOfficialData(Request $request, ThesisProject $project)
    {
        $validated = $request->validate([
            'type_of_research' => 'nullable|string|max:255',
            'promotion_year' => 'nullable|string|max:4',
            'specialty_resolution' => 'nullable|string|max:255',
            'document_correlative' => 'nullable|string|max:255',
        ]);

        $project->update($validated);

        return back()->with('success', 'Datos oficiales actualizados correctamente.');
    }

    public function downloadOficio(ThesisProject $project)
    {
        // 1. Cargamos toda la data necesaria (importante cargar jurados y autores)
        $project->load([
            'authors.enrollments.studyPlan.studyProgram',
            'advisor',
            'jurors.teacher',
            'defenseAct'
        ]);

        // 2. Preparar Logos
        $logoMinedu = $this->convertImageToBase64(public_path('img/logo-minedu.png'));
        $logoInsti = $this->convertImageToBase64(public_path('img/logo-instituto.png'));

        // 3. Generar PDF usando una nueva vista Blade
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.thesis_oficio', [
            'project' => $project,
            'logoMinedu' => $logoMinedu,
            'logoInsti' => $logoInsti,
        ]);

        return $pdf->stream("Oficio_{$project->document_correlative}.pdf");
    }

    public function downloadActaTitulacion(ThesisProject $project)
    {
        // CARGA CRÍTICA: Traemos autores, sus carreras, el asesor, los jurados y el acta de defensa
        $project->load([
            'authors.enrollments.studyPlan.studyProgram',
            'advisor',
            'jurors.teacher',
            'defenseAct'
        ]);

        if (!$project->defenseAct) {
            return back()->with('error', 'No hay acta de sustentación registrada.');
        }

        $logoMinedu = $this->convertImageToBase64(public_path('img/logo-minedu.png'));
        $logoInsti = $this->convertImageToBase64(public_path('img/logo-instituto.png'));

        // Obtenemos la carrera del primer tesista (Anderson) para el título
        $carrera = $project->authors->first()->enrollments->first()->studyPlan->studyProgram->name ?? 'EDUCACIÓN';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.thesis_acta_titulacion', [
            'project' => $project,
            'carrera' => $carrera,
            'ds_creacion' => 'D.S. Nº 08-83-ED / D.S. Nº 017-2002-ED',
            'logoMinedu' => $logoMinedu,
            'logoInsti' => $logoInsti,
            'numberHelper' => new \App\Helpers\NumberHelper()
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream("Acta_Titulacion_{$project->id}.pdf");
    }

    public function downloadNomina(ThesisProject $project)
    {
        // Cargamos la data necesaria
        $project->load(['authors.enrollments.studyPlan.studyProgram', 'advisor', 'defenseAct']);

        $logoMinedu = $this->convertImageToBase64(public_path('img/logo-minedu.png'));
        $logoInsti = $this->convertImageToBase64(public_path('img/logo-instituto.png'));

        // Datos institucionales que acordamos manejar desde el controlador
        $carrera = $project->authors->first()->enrollments->first()->studyPlan->studyProgram->name ?? 'EDUCACIÓN';
        $ds_creacion = 'D.S. Nº 08-83-ED / D.S. Nº 017-2002-ED';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.thesis_nomina', [
            'project' => $project,
            'carrera' => $carrera,
            'ds_creacion' => $ds_creacion,
            'logoMinedu' => $logoMinedu,
            'logoInsti' => $logoInsti,
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream("Nomina_Expeditos_{$project->id}.pdf");
    }

    private function convertImageToBase64($path)
    {
        if (!file_exists($path)) {
            return null;
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

}
