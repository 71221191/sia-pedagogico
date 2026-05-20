<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use App\Models\Course; // Para obtener los cursos
use App\Models\AcademicPeriod; // Para obtener los períodos
use App\Models\User; // Para obtener los docentes
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Models\StudyProgram;
use App\Models\StudyPlan;
use App\Services\SectionService;
use Illuminate\Support\Facades\DB;

class CourseSectionController extends Controller
{
    /**
     * Muestra la lista de secciones de cursos.
     */
    public function index(Request $request)
    {
        // 1. Traemos TODOS los cursos (sin paginate) para que las tarjetas se armen completas
        $courseSections = CourseSection::with(['course.studyPlan.studyProgram', 'academicPeriod', 'teacher'])
                                ->orderBy('academic_period_id', 'desc')
                                ->get();

        // 2. Lo mandamos como 'courseSections' pero dentro de un array 'data'
        // para que el Index.vue no explote al buscar .data
        return Inertia::render('Admin/CourseSections/Index', [
            'courseSections'  => [
                'data' => $courseSections
            ],
            'academicPeriods' => \App\Models\AcademicPeriod::orderBy('start_date', 'desc')->get(),
            'studyPlans'      => \App\Models\StudyPlan::with('studyProgram')->get(),
        ]);
    }
    /**
     * Muestra el formulario para crear una nueva sección de curso.
     */
    public function create()
    {
        Log::info('[CourseSectionController@create] Mostrando formulario de creación de sección de curso.');

        $studyPrograms = StudyProgram::orderBy('name')->get(['id', 'name', 'short_name']);
        $studyPlans = StudyPlan::with('studyProgram')->orderBy('study_program_id')->orderBy('name')->get(['id', 'study_program_id', 'name']);

        // ¡IMPORTANTE! Asegurarnos que 'cycle' se esté seleccionando de la base de datos
        $courses = Course::with('studyPlan.studyProgram')
                         ->orderBy('study_plan_id')->orderBy('name')
                         ->get(['id', 'study_plan_id', 'name', 'code', 'cycle']); // <-- Asegúrate de que 'cycle' esté aquí

        Log::info('[CourseSectionController@create] Primeros 5 cursos antes de mapear: ' . json_encode($courses->take(5))); // <-- NUEVO LOG

        $academicPeriods = AcademicPeriod::orderBy('start_date', 'desc')->get(['id', 'name', 'status']);

        // 1. Buscamos los usuarios con rol docente y cargamos su relación 'person'
        $teachers = User::role('docente')->with('person')->get();

        // 2. Mapeamos la colección
        $teachers = $teachers->map(function ($userDocente) {
            // IMPORTANTE: Si el usuario no tiene una persona vinculada, lo saltamos para evitar errores
            if (!$userDocente->person) return null;

            return [
                'id' => $userDocente->person->id, // <--- LA CLAVE: Mandamos el ID de la tabla PEOPLE (2578)
                'name' => $userDocente->person->full_name ?? $userDocente->username,
            ];
        })->filter()->values(); // Limpiamos los nulos si los hubiera

        return Inertia::render('Admin/CourseSections/Create', [
            'studyPrograms' => $studyPrograms,
            'studyPlans' => $studyPlans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'study_program_id' => $plan->study_program_id,
                    'name' => $plan->name . ' (' . ($plan->studyProgram->short_name ?? 'N/A') . ')',
                ];
            }),
            'courses' => $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'study_plan_id' => $course->study_plan_id,
                    'name' => $course->name . ' (' . $course->code . ')',
                    'cycle' => $course->cycle, // <-- Asegúrate de que 'cycle' se pase aquí
                ];
            }),
            'academicPeriods' => $academicPeriods,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Guarda una nueva sección de curso en la base de datos.
     */
    public function store(Request $request)
    {
        Log::info('[CourseSectionController@store] Intentando guardar nueva sección de curso.');
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('course_sections')->where(function ($query) use ($request) {
                    return $query->where('course_id', $request->course_id)
                                 ->where('academic_period_id', $request->academic_period_id);
                }),
            ],
            'teacher_id' => 'nullable|exists:users,id', // Se valida que exista como usuario
            'vacancy_limit' => 'required|integer|min:0',
            // is_closed, acta_number, acta_close_date no se establecen al crear
        ]);

        CourseSection::create($validated);
        Log::info('[CourseSectionController@store] Sección de curso creada con éxito: ' . $validated['name']);

        return redirect()->route('admin.course_sections.index')
                         ->with('success', 'Sección de curso creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una sección de curso existente.
     */
    public function edit(CourseSection $courseSection)
    {
        Log::info('[CourseSectionController@edit] Editando sección de curso ID: ' . $courseSection->id);

        $courseSection->load('course.studyPlan.studyProgram');

        $studyPrograms = StudyProgram::orderBy('name')->get(['id', 'name', 'short_name']);
        $studyPlans = StudyPlan::with('studyProgram')->orderBy('study_program_id')->orderBy('name')->get(['id', 'study_program_id', 'name']);
        // ¡IMPORTANTE! Asegurarnos que 'cycle' se esté seleccionando de la base de datos
        $courses = Course::with('studyPlan.studyProgram')->orderBy('study_plan_id')->orderBy('name')->get(['id', 'study_plan_id', 'name', 'code', 'cycle']); // <-- Asegúrate de que 'cycle' esté aquí

        Log::info('[CourseSectionController@edit] Primeros 5 cursos antes de mapear: ' . json_encode($courses->take(5))); // <-- NUEVO LOG

        $academicPeriods = AcademicPeriod::orderBy('start_date', 'desc')->get(['id', 'name', 'status']);

        // 1. Buscamos los usuarios con rol docente y cargamos su relación 'person'
        $teachers = User::role('docente')->with('person')->get();

        // 2. Mapeamos la colección
        $teachers = $teachers->map(function ($userDocente) {
            // IMPORTANTE: Si el usuario no tiene una persona vinculada, lo saltamos para evitar errores
            if (!$userDocente->person) return null;

            return [
                'id' => $userDocente->person->id, // <--- LA CLAVE: Mandamos el ID de la tabla PEOPLE (2578)
                'name' => $userDocente->person->full_name ?? $userDocente->username,
            ];
        })->filter()->values(); // Limpiamos los nulos si los hubiera

        return Inertia::render('Admin/CourseSections/Edit', [
            'courseSection' => $courseSection,
            'studyPrograms' => $studyPrograms,
            'studyPlans' => $studyPlans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'study_program_id' => $plan->study_program_id,
                    'name' => $plan->name . ' (' . ($plan->studyProgram->short_name ?? 'N/A') . ')',
                ];
            }),
            'courses' => $courses->map(function ($course) { // <-- También pasar 'cycle' mapeado
                return [
                    'id' => $course->id,
                    'study_plan_id' => $course->study_plan_id,
                    'name' => $course->name . ' (' . $course->code . ')',
                    'cycle' => $course->cycle, // <-- NUEVO: Incluir ciclo
                ];
            }),
            'academicPeriods' => $academicPeriods,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Actualiza una sección de curso existente en la base de datos.
     */
    public function update(Request $request, CourseSection $courseSection)
    {
        Log::info('[CourseSectionController@update] Intentando actualizar sección de curso ID: ' . $courseSection->id);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',

            'academic_period_id' => 'required|exists:academic_periods,id',
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('course_sections')->where(function ($query) use ($request, $courseSection) {
                    return $query->where('course_id', $request->course_id)
                                 ->where('academic_period_id', $request->academic_period_id)
                                 ->where('id', '!=', $courseSection->id); // Ignorar el propio ID al actualizar
                }),
            ],
            'teacher_id' => 'nullable|exists:users,id',
            'vacancy_limit' => 'required|integer|min:0',
            'is_closed' => 'boolean',
            'acta_number' => 'nullable|string|max:100',
            'acta_close_date' => 'nullable|date',
        ]);

        $courseSection->update($validated);
        \App\Models\Schedule::where('course_section_id', $courseSection->id)
            ->update(['teacher_id' => $validated['teacher_id']]);

        return redirect()->route('admin.course_sections.index')
                        ->with('success', 'Sección y horario actualizados correctamente.');
    }

    /**
     * Elimina una sección de curso.
     */
    public function destroy(CourseSection $courseSection)
    {
        Log::info('[CourseSectionController@destroy] Intentando eliminar sección de curso ID: ' . $courseSection->id);
        // NOTA: Implementar RF-060. Una sección no debería eliminarse si tiene matrículas asociadas.
        $courseSection->delete();
        Log::info('[CourseSectionController@destroy] Sección de curso ID: ' . $courseSection->id . ' eliminada.');

        return redirect()->route('admin.course_sections.index')
                         ->with('success', 'Sección de curso eliminada exitosamente.');
    }

    // Inyectamos el servicio en el constructor si no lo tenías
    protected \App\Services\SectionService $sectionService;


    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    /**
     * Muestra el asistente de generación masiva.
     */
    public function bulkCreate()
    {
        return Inertia::render('Admin/CourseSections/BulkCreate', [
            'academicPeriods' => \App\Models\AcademicPeriod::where('status', '!=', 'closed')->get(),
            'studyPlans' => \App\Models\StudyPlan::with('studyProgram')->get(),
            // Definimos los ciclos del I al X para los checkboxes
            'availableCycles' => ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X']
        ]);
    }

    /**
     * Procesa la creación masiva.
     */
    public function bulkStore(Request $request)
    {
        // 1. Validamos los nuevos campos que vienen de la vista
        $request->validate([
            'academic_period_id' => 'required|exists:academic_periods,id',
            'study_plan_id'      => 'required|exists:study_plans,id',
            'cycles'             => 'required|array|min:1',
            'section_labels'     => 'required|array|min:1', // Para las letras A, B...
            'shift_id'           => 'required|exists:shifts,id', // Para Mañana/Tarde
        ]);

        // 2. Llamamos al servicio pasando los 5 parámetros
        $count = $this->sectionService->generateBulkSections(
            $request->academic_period_id,
            $request->study_plan_id,
            $request->cycles,
            $request->section_labels,
            $request->shift_id
        );

        // 3. Redirigimos a la asignación de docentes con los filtros puestos
        return redirect()->route('admin.course_sections.teacher-assignment', [
            'academic_period_id' => $request->academic_period_id,
            'study_plan_id'      => $request->study_plan_id
        ])->with('success', "Se generaron {$count} secciones exitosamente para el turno seleccionado.");
    }

    /**
     * Muestra la tabla masiva para asignar docentes a las secciones de un plan/periodo.
     */
    public function teacherAssignment(Request $request)
    {
        $request->validate([
            'academic_period_id' => 'required|exists:academic_periods,id',
            'study_plan_id' => 'required|exists:study_plans,id',
        ]);

        // 1. Traemos las secciones filtradas
        $sections = CourseSection::with(['course', 'teacher'])
            ->where('academic_period_id', $request->academic_period_id)
            ->whereHas('course', function($q) use ($request) {
                $q->where('study_plan_id', $request->study_plan_id);
            })
            ->get()
            ->sortBy('course.cycle'); // Ordenamos por ciclo para que sea fácil de leer

        // 2. Traemos a todos los docentes disponibles (ID y Nombre)
        $teachers = \App\Models\Person::whereHas('user.roles', function($q) {
            $q->where('name', 'docente');
        })->get(['id', 'names', 'last_name_p', 'last_name_m']);

        return Inertia::render('Admin/CourseSections/TeacherAssignment', [
            'sections' => $sections->values(),
            'teachers' => $teachers,
            'period' => \App\Models\AcademicPeriod::find($request->academic_period_id),
            'plan' => \App\Models\StudyPlan::with('studyProgram')->find($request->study_plan_id),
        ]);
    }

    /**
     * Procesa la actualización masiva de docentes y vacantes.
     */
    public function updateBulk(Request $request)
    {
        $request->validate([
            'assignments' => 'required|array',
            'assignments.*.id' => 'required|exists:course_sections,id',
            'assignments.*.teacher_id' => 'nullable|exists:people,id',
            'assignments.*.vacancy_limit' => 'required|integer|min:1',
        ]);

        // Usamos una transacción para que todo sea seguro
        DB::transaction(function () use ($request) {
            foreach ($request->assignments as $item) {
                CourseSection::where('id', $item['id'])->update([
                    'teacher_id' => $item['teacher_id'],
                    'vacancy_limit' => $item['vacancy_limit']
                ]);
            }
        });

        return redirect()->route('admin.course_sections.index')
            ->with('success', 'Carga académica y vacantes actualizadas con éxito.');
    }
}
