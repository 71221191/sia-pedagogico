<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\SocioeconomicController;
use App\Http\Controllers\Admin\ImportController;
use App\Imports\StudentsImport;
use App\Imports\CoursesImport;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Admin\AcademicPeriodController;
use App\Http\Controllers\Admin\StudyProgramController;
use App\Http\Controllers\Admin\StudyPlanController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseSectionController;
use App\Http\Controllers\DashboardController; // Este es el del alumno
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // Este es el del admin
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TreasuryController;
use App\Http\Controllers\Teacher\SectionController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\Admin\CompetencyController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Student\ProgressController;
use App\Http\Controllers\Teacher\PortfolioController;
use App\Http\Controllers\HeadOfArea\PortfolioValidationController;
use App\Http\Controllers\Admin\PracticeCenterController;
use App\Http\Controllers\Admin\PracticeAssignmentController;
use App\Http\Controllers\Teacher\PracticeEvaluationController;
use App\Http\Controllers\Student\ThesisController as StudentThesisController;
use App\Http\Controllers\Admin\ThesisController as AdminThesisController;
use App\Http\Controllers\Teacher\ThesisReviewController;
use App\Http\Controllers\Admin\IdentityController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Teacher\AcademicUnitController;
use App\Http\Controllers\Teacher\AvailabilityController;
use App\Http\Controllers\Teacher\LearningResourceController;
use App\Http\Controllers\Teacher\TaskController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ImportHistoryController;
use App\Http\Controllers\Student\CourseContentController;
use App\Http\Controllers\Teacher\LearningForumController;
use App\Http\Controllers\Student\TaskSubmissionController as StudentTaskController;
use App\Http\Controllers\Teacher\TaskSubmissionController as TeacherTaskController;
use App\Http\Controllers\Teacher\SyllabusController;

// 1. RUTA DE BIENVENIDA (Landing Page)

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// 2. RUTAS PROTEGIDAS POR LOGIN
Route::middleware(['auth', 'verified'])->group(function () {

    // --- 1. RUTAS DE ESTUDIANTE (Específicas) ---
// --- 1. RUTAS DE ESTUDIANTE (Específicas) ---
    Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->name('student.')->group(function () {

        // El Récord Académico / Kárdex (Mi Progreso)
        Route::get('/mi-progreso', [ProgressController::class, 'index'])->name('progress.index');
        Route::get('/mi-progreso/descargar-pdf', [ProgressController::class, 'downloadPdf'])->name('progress.pdf');

        // NUEVO: Gestión de Cursos Activos para el Alumno
        Route::get('/cursos', [App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses.index');
        Route::get('/cursos/{section}', [App\Http\Controllers\Student\CourseController::class, 'show'])->name('courses.show');

        // Mi Horario
        Route::get('/mi-horario', [ProgressController::class, 'mySchedule'])->name('schedule');
        Route::get('/mi-horario/pdf', [ProgressController::class, 'downloadSchedulePdf'])->name('schedule.pdf');
        Route::get('/mi-horario/excel', [ProgressController::class, 'downloadScheduleExcel'])->name('schedule.excel');

        // Registro de Tesis
        Route::prefix('tesis')->name('thesis.')->group(function () {
            Route::get('/', [StudentThesisController::class, 'index'])->name('index');
            Route::get('/registrar', [StudentThesisController::class, 'create'])->name('create');
            Route::post('/registrar', [StudentThesisController::class, 'store'])->name('store');
            Route::post('/{project}/documento', [StudentThesisController::class, 'uploadDocument'])->name('upload-document');
        });

        // Ruta para descargar la constancia de matrícula en PDF
        Route::get('/matricula/descargar-pdf/{enrollment}', [EnrollmentController::class, 'downloadPdf'])->name('enrollment.pdf');
    });

    // --- 2. RUTAS GENERALES ---
    Route::middleware(['auth'])->group(function () {

        // El Dashboard es UNIVERSAL
        Route::get('/dashboard', App\Http\Controllers\DashboardController::class)->name('dashboard');

        // Ficha y Pagos son para el alumno pero con nombres LIMPIOS
        Route::get('/ficha-socioeconomica', [SocioeconomicController::class, 'create'])->name('socioeconomic.create');
        Route::post('/ficha-socioeconomica', [SocioeconomicController::class, 'store'])->name('socioeconomic.store');

        Route::get('/mis-pagos', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/mis-pagos', [PaymentController::class, 'store'])->name('payments.store');

        Route::put('/mis-pagos/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/mis-pagos/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

        // Matrícula (Protegida por la ficha)
        Route::middleware(['check.socioeconomic'])->group(function () {
            Route::get('/matricula', [EnrollmentController::class, 'create'])->name('enrollment.create');
            Route::post('/matricula', [EnrollmentController::class, 'store'])->name('enrollment.store');
        });
    });

    // --- ZONA ADMINISTRATIVA (Protegida) ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // 1. Dashboard Admin
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('dashboard');

        Route::resource('centros-practica', PracticeCenterController::class)->only(['index', 'store', 'update']);

        Route::resource('asignaciones-practica', PracticeAssignmentController::class)->only(['index', 'store', 'destroy']);

        // 2. Gestión de Estudiantes
        Route::get('/estudiantes', [StudentController::class, 'index'])->name('students.index');
        Route::get('/estudiantes/{id}', [StudentController::class, 'show'])->name('students.show');

        // 3. MANTENEDORES (CRUDs Optimizados)
        // Usamos 'parameters' para mantener compatibilidad con tus variables {academicPeriod}, etc.

        // Periodos
        Route::resource('periodos', AcademicPeriodController::class)
            ->names('academic_periods')
            ->parameters(['periodos' => 'academicPeriod']);
        Route::post('/periodos/{academicPeriod}/open', [AcademicPeriodController::class, 'openPeriod'])->name('academic_periods.open');

        // Programas (Carreras)
        Route::resource('programas', StudyProgramController::class)
            ->names('study_programs')
            ->parameters(['programas' => 'studyProgram']);

        // Planes de Estudio
        Route::resource('planes', StudyPlanController::class)
            ->names('study_plans')
            ->parameters(['planes' => 'studyPlan']);

        // Cursos
        Route::resource('cursos', CourseController::class)
            ->names('courses')
            ->parameters(['cursos' => 'course']);

        // Rutas para generación masiva
        Route::get('/secciones-cursos/masivo', [CourseSectionController::class, 'bulkCreate'])->name('course_sections.bulk-create');
        Route::post('/secciones-cursos/masivo', [CourseSectionController::class, 'bulkStore'])->name('course_sections.bulk-store');

        // Rutas para asignación rápida de docentes
        Route::get('/secciones-cursos/asignacion-docentes', [CourseSectionController::class, 'teacherAssignment'])->name('course_sections.teacher-assignment');
        Route::post('/secciones-cursos/update-bulk', [CourseSectionController::class, 'updateBulk'])->name('course_sections.update-bulk');

        // Secciones
        Route::resource('secciones-cursos', CourseSectionController::class)
            ->names('course_sections')
            ->parameters(['secciones-cursos' => 'courseSection']);

        Route::get('/estudiantes/{personId}/certificado', [ReportController::class, 'downloadCertificate'])
            ->name('students.certificate');

        // CRUD de Competencias
        Route::get('/competencias', [CompetencyController::class, 'index'])->name('competencies.index');
        Route::post('/competencias', [CompetencyController::class, 'store'])->name('competencies.store');

        Route::post('/dominios', [DomainController::class, 'store'])->name('domains.store');
        Route::delete('/dominios/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy');
        Route::post('/estudiantes/{person}/foto', [IdentityController::class, 'uploadPhoto'])->name('students.photo');

        Route::prefix('tesis')->name('thesis.')->group(function () {
            Route::get('/', [AdminThesisController::class, 'index'])->name('index');
            Route::get('/{project}', [AdminThesisController::class, 'show'])->name('show');
            Route::patch('/{project}/asesor', [AdminThesisController::class, 'assignAdvisor'])->name('assign-advisor');
            Route::post('/{project}/jurados', [App\Http\Controllers\Admin\ThesisController::class, 'assignJurors'])
                ->name('assign-jurors');
            // También agrega de una vez la de sustentación para que no te dé error luego:
            Route::post('/{project}/sustentacion', [App\Http\Controllers\Admin\ThesisController::class, 'recordDefense'])
                ->name('record-defense');
            Route::patch('/{project}/datos-oficiales', [AdminThesisController::class, 'updateOfficialData'])->name('update-official-data');
            Route::get('/{project}/pdf-oficio', [AdminThesisController::class, 'downloadOficio'])->name('download-oficio');
            Route::get('/{project}/pdf-acta-titulacion', [AdminThesisController::class, 'downloadActaTitulacion'])->name('download-acta-titulacion');
            Route::get('/{project}/pdf-nomina', [AdminThesisController::class, 'downloadNomina'])->name('download-nomina');
            Route::patch('/{project}/programar', [AdminThesisController::class, 'scheduleDefense'])->name('schedule-defense');
        });

        Route::resource('ambientes', ClassroomController::class)->names('classrooms');
        Route::get('/secciones-cursos/{courseSection}/horario', [ScheduleController::class, 'edit'])->name('course_sections.schedule.edit');
        Route::post('/secciones-cursos/{courseSection}/horario', [ScheduleController::class, 'store'])->name('course_sections.schedule.store');
        Route::delete('/horarios/{schedule}', [ScheduleController::class, 'destroy'])->name('course_sections.schedule.destroy');

        Route::get('/importaciones/historial', [ImportHistoryController::class, 'index'])->name('imports.history');
        Route::get('/importaciones/descargar/{import}', [ImportHistoryController::class, 'download'])->name('imports.download');

        Route::get('/reportes/nomina-matricula/{courseSection}', [ReportController::class, 'nominaMatricula'])
            ->name('reports.nomina');

        Route::get('/reportes/cuadro-estadistico', [ReportController::class, 'cuadroEstadistico'])
            ->name('reports.cuadro-estadistico');
    });

    // Solo Tesorería y Admin pueden entrar aquí
    Route::middleware(['auth', 'role:tesoreria|admin'])
        ->prefix('tesoreria')
        ->name('tesoreria.')
        ->group(function () {

            // Listado principal
            Route::get('/validar-pagos', [TreasuryController::class, 'index'])->name('payments.index');

            // Acción de aprobar/rechazar
            Route::patch('/pagos/{payment}/verify', [TreasuryController::class, 'verify'])->name('payments.verify');
        });

    Route::middleware(['auth', 'role:jefe_de_area|admin'])
        ->prefix('jefe-area')
        ->name('head_of_area.')
        ->group(function () {

            // Dashboard de validación de documentos
            Route::get('/validar-portafolio', [PortfolioValidationController::class, 'index'])->name('portfolio.index');

            // Acción de aprobar u observar
            Route::patch('/validar-portafolio/{portfolio}', [PortfolioValidationController::class, 'update'])->name('portfolio.update');
        });

    Route::middleware(['auth', 'role:docente|admin'])->prefix('docente')->name('teacher.')->group(function () {

        // 1. Ver mis cursos
        Route::get('/secciones', [SectionController::class, 'index'])->name('sections.index');

        // 2. Ver la sábana de notas (Alumnos y cuadros de texto)
        Route::get('/secciones/{section}', [SectionController::class, 'show'])->name('sections.show');

        // 3. Guardar las notas (POST)
        Route::post('/secciones/{section}/notas', [GradeController::class, 'store'])->name('grades.store');

        Route::get('/secciones/{section}/configurar', [SectionController::class, 'configure'])->name('sections.configure');
        Route::post('/secciones/{section}/configurar', [SectionController::class, 'setCompetencies'])->name('sections.set-competencies');

        Route::put('/dominios/{domain}', [DomainController::class, 'update'])->name('domains.update');
        Route::put('/competencias/{competency}', [CompetencyController::class, 'update'])->name('competencies.update');

        Route::patch('/secciones/{section}/cerrar', [SectionController::class, 'close'])->name('sections.close');

        Route::get('/secciones/{section}/pdf', [SectionController::class, 'pdf'])
            ->name('sections.pdf');

        Route::get('/mis-practicantes', [PracticeEvaluationController::class, 'index'])->name('practice.index');

        Route::post('/mis-practicantes/{assignment}/evaluar', [PracticeEvaluationController::class, 'store'])->name('practice.store');

        // Gestión de Sílabo Único (Reemplaza al antiguo portafolio)
        Route::prefix('silabo')->name('syllabus.')->group(function () {
            // 1. Ver la pantalla de sílabo de una sección
            Route::get('/seccion/{section}', [SyllabusController::class, 'index'])->name('index');

            // 2. Subir o reemplazar el sílabo
            Route::post('/seccion/{section}/subir', [SyllabusController::class, 'store'])->name('store');

            // 3. Eliminar el sílabo de la sección
            Route::delete('/seccion/{section}/eliminar', [SyllabusController::class, 'destroy'])->name('destroy');
        });

        Route::get('/revision-tesis', [App\Http\Controllers\Teacher\ThesisReviewController::class, 'index'])
            ->name('thesis-review.index'); // <--- AHORA SÍ, EL NOMBRE FINAL SERÁ 'teacher.thesis-review.index'

        Route::patch('/revision-tesis/{project}/calificar', [ThesisReviewController::class, 'updateScore'])
            ->name('thesis-review.update-score');

        Route::get('/mi-disponibilidad', [AvailabilityController::class, 'index'])->name('availability.index');
        Route::post('/mi-disponibilidad', [AvailabilityController::class, 'store'])->name('availability.store');

        // Revisión y Calificación de Entregas
    });
});

// 3. CONFIGURACIONES ADICIONALES (Profile, etc.)
require __DIR__ . '/settings.php';


/*
|--------------------------------------------------------------------------
| MÓDULO DE IMPORTACIÓN MASIVA (ADMIN)
|--------------------------------------------------------------------------
*/

// Ruta para mostrar la interfaz de importación
Route::get('/admin/importaciones', function () {
    return Inertia::render('Admin/Imports/Index');
})->name('admin.imports.index');

// Ruta única para procesar la importación
Route::post('/admin/importaciones/procesar', [ImportController::class, 'process'])
    ->name('admin.imports.process');

// Ruta específica para importar alumnos activos 2026-I
Route::post('/admin/importaciones/alumnos-activos', [ImportController::class, 'importActiveStudents'])
    ->name('admin.imports.active-students');
