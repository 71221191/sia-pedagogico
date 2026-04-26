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
use App\Http\Controllers\Teacher\AvailabilityController;
use App\Http\Controllers\Admin\ScheduleController;


// 1. RUTA DE BIENVENIDA (Landing Page)

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// 2. RUTAS PROTEGIDAS POR LOGIN
Route::middleware(['auth', 'verified'])->group(function () {

    // --- 1. RUTAS DE ESTUDIANTE (Específicas) ---
    Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->name('student.')->group(function () {
        Route::get('/mi-progreso', [ProgressController::class, 'index'])->name('progress.index');

        Route::prefix('tesis')->name('thesis.')->group(function () {
            Route::get('/', [StudentThesisController::class, 'index'])->name('index');
            Route::get('/registrar', [StudentThesisController::class, 'create'])->name('create');
            Route::post('/registrar', [StudentThesisController::class, 'store'])->name('store');
            Route::post('/{project}/documento', [StudentThesisController::class, 'uploadDocument'])->name('upload-document');
        });
        Route::get('/mi-progreso/descargar-pdf', [ProgressController::class, 'downloadPdf'])->name('progress.pdf');
        Route::get('/mi-horario', [ProgressController::class, 'mySchedule'])->name('schedule');

    });

    // --- 2. RUTAS GENERALES (Sin el prefijo student.) ---
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

        Route::get('/mi-horario/descargar', [ProgressController::class, 'downloadSchedulePdf'])->name('schedule.pdf');
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

        Route::prefix('asistencia')->name('attendance.')->group(function () {
            // 1. Ver lista de sesiones (clases) de una sección
            Route::get('/seccion/{section}', [AttendanceController::class, 'index'])->name('index');

            // 2. Guardar una nueva sesión de clase (el encabezado)
            Route::post('/seccion/{section}/sesion', [AttendanceController::class, 'storeSession'])->name('store-session');

            // 3. Ver la hoja de asistencia de una sesión específica
            Route::get('/sesion/{session}', [AttendanceController::class, 'show'])->name('show');

            // 4. Guardar los checks de asistencia
            Route::post('/sesion/{session}/registrar', [AttendanceController::class, 'storeRecords'])->name('store-records');
        });

        Route::prefix('portafolio')->name('portfolio.')->group(function () {
            // 1. Ver los archivos de una sección
            Route::get('/seccion/{section}', [PortfolioController::class, 'index'])->name('index');

            // 2. Subir un nuevo archivo
            Route::post('/seccion/{section}/subir', [PortfolioController::class, 'store'])->name('store');

            // 3. Eliminar un archivo (solo si está pendiente)
            Route::delete('/archivo/{portfolio}', [PortfolioController::class, 'destroy'])->name('destroy');
        });

        Route::get('/revision-tesis', [App\Http\Controllers\Teacher\ThesisReviewController::class, 'index'])
            ->name('thesis-review.index'); // <--- AHORA SÍ, EL NOMBRE FINAL SERÁ 'teacher.thesis-review.index'

        Route::patch('/revision-tesis/{project}/calificar', [ThesisReviewController::class, 'updateScore'])
            ->name('thesis-review.update-score');

        Route::get('/mi-disponibilidad', [AvailabilityController::class, 'index'])->name('availability.index');
        Route::post('/mi-disponibilidad', [AvailabilityController::class, 'store'])->name('availability.store');
    });
});

// 3. CONFIGURACIONES ADICIONALES (Profile, etc.)
require __DIR__ . '/settings.php';


/*
|--------------------------------------------------------------------------
| SECCIÓN DE IMPORTACIÓN (ADMIN)
|--------------------------------------------------------------------------
*/

// --- RUTAS PARA ALUMNOS (Excel 1) ---
Route::post('/importar-alumnos', [ImportController::class, 'importStudents'])->name('import.students');

// Ruta temporal de prueba rápida para alumnos
Route::get('/test-import-alumnos', function () {
    ini_set('max_execution_time', 0);
    set_time_limit(0);
    // Cambia el nombre si tu archivo de alumnos se llama distinto en storage/app
    Excel::import(new StudentsImport, storage_path('app/alumnos.xlsx'));
    return "Importación de alumnos completada con éxito.";
});


// --- RUTAS PARA CURSOS (Excel 2) ---

// A. Ruta para MOSTRAR el formulario de subida
Route::get('/subir-cursos-test', function () {

    // Preparar el HTML de los errores si existen en la sesión
    $htmlErrores = '';
    if (session('detalles_errores') && count(session('detalles_errores')) > 0) {
        $htmlErrores = '
        <div style="color: #842029; background: #f8d7da; border: 1px solid #f5c2c7; padding: 15px; margin: 20px 0; font-family: sans-serif; border-radius: 5px;">
            <h3 style="margin-top:0">⚠️ Detalles encontrados durante la importación:</h3>
            <ul style="max-height: 250px; overflow-y: auto;">';
        foreach (session('detalles_errores') as $error) {
            $htmlErrores .= '<li>' . $error . '</li>';
        }
        $htmlErrores .= '</ul>
        </div>';
    }

    return '
        <div style="font-family: sans-serif; max-width: 800px; margin: 50px auto; padding: 30px; border: 1px solid #eee; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <h1 style="color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px;">Importador de Cursos Académicos</h1>

            <!-- Mensaje de éxito -->
            ' . (session('success') ? '
                <div style="color: #0f5132; background: #d1e7dd; border: 1px solid #badbcc; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: bold;">
                    ' . session('success') . '
                </div>
            ' : '') . '

            <!-- Cuadro de errores -->
            ' . $htmlErrores . '

            <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; border: 1px solid #e9ecef;">
                <p style="margin-top:0; color: #666;">Selecciona el archivo Excel de Cursos (Catálogo de 1,400 registros).</p>

                <form action="' . route('import.courses') . '" method="POST" enctype="multipart/form-data">
                    ' . csrf_field() . '

                    <div style="margin-bottom: 20px;">
                        <input type="file" name="file" required style="font-size: 16px;">
                    </div>

                    <button type="submit" style="background: #198754; color: white; padding: 12px 25px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; width: 100%;">
                        🚀 INICIAR IMPORTACIÓN DE CURSOS
                    </button>
                </form>
            </div>

            <div style="margin-top: 30px; font-size: 13px; color: #7f8c8d; line-height: 1.6;">
                <strong>Nota profesional:</strong>
                <ul style="margin-top: 5px;">
                    <li>El sistema detectará automáticamente los Planes con RVM mediante paréntesis.</li>
                    <li>Los cursos sin RVM se asignarán automáticamente al <strong>PLAN ANTIGUO</strong>.</li>
                    <li>El DNI se usará como llave única para evitar duplicar personas.</li>
                </ul>
            </div>
        </div>
    ';
});

// B. Ruta para PROCESAR el archivo de cursos (POST)
Route::post('/importar-cursos-proceso', [ImportController::class, 'importCourses'])->name('import.courses');

Route::post('/importar-alumnos-activos', [ImportController::class, 'importActiveStudents'])->name('import.active_students');

// RUTA POST
Route::post('/importar-notas-proceso', [ImportController::class, 'importGrades'])->name('import.grades');

// Ruta para que el navegador pregunte el progreso (AJAX)
Route::get('/import-status/{id}', function ($id) {
    return response()->json([
        'current' => Cache::get("import_progress_{$id}", 0),
        'total' => Cache::get("import_total_{$id}", 0)
    ]);
});

// RUTA GET (Formulario)
// Vista con Barra de Progreso
Route::get('/subir-notas-historico', function () {
    $importId = uniqid(); // Generamos un ID único para esta subida
    return '
        <div style="font-family:sans-serif; max-width:600px; margin:50px auto; padding:30px; border:1px solid #ccc; border-radius:10px;">
            <h2>Importador con Progreso Real (14k)</h2>

            <form id="uploadForm" enctype="multipart/form-data">
                ' . csrf_field() . '
                <input type="hidden" name="import_id" value="' . $importId . '">
                <input type="file" name="file" id="fileInput" required><br><br>
                <button type="submit" id="btnSubir" style="background:#d35400; color:white; padding:10px 20px; border:none; cursor:pointer;">🚀 EMPEZAR IMPORTACIÓN</button>
            </form>

            <div id="progressContainer" style="display:none; margin-top:30px;">
                <p id="statusText">Procesando filas: <span id="current">0</span> de <span id="total">0</span></p>
                <div style="width:100%; background:#eee; height:25px; border-radius:15px; overflow:hidden;">
                    <div id="progressBar" style="width:0%; background:#27ae60; height:100%; transition:width 0.5s;"></div>
                </div>
                <p id="percentText" style="text-align:right; font-weight:bold;">0%</p>
            </div>
        </div>

        <script>
            const form = document.getElementById("uploadForm");
            form.onsubmit = async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                document.getElementById("btnSubir").disabled = true;
                document.getElementById("progressContainer").style.display = "block";

                // Enviar archivo
                fetch("' . route('import.grades') . '", { method: "POST", body: formData });

                // Empezar a preguntar el progreso cada 2 segundos
                const interval = setInterval(async () => {
                    const res = await fetch("/import-status/' . $importId . '");
                    const data = await res.json();

                    if (data.current === "COMPLETO") {
                        document.getElementById("progressBar").style.width = "100%";
                        document.getElementById("percentText").innerText = "¡COMPLETO!";
                        clearInterval(interval);
                        alert("Importación finalizada con éxito.");
                    } else if (data.total > 0) {
                        const percent = Math.round((data.current / data.total) * 100);
                        document.getElementById("current").innerText = data.current;
                        document.getElementById("total").innerText = data.total;
                        document.getElementById("progressBar").style.width = percent + "%";
                        document.getElementById("percentText").innerText = percent + "%";
                    }
                }, 2000);
            };
        </script>
    ';
});

Route::get('/subir-alumnos-2025', function () {
    $listaErrores = '';
    if (session('detalles_errores')) {
        $listaErrores = '<div style="color:red; background:#fff1f0; border:1px solid red; padding:15px; margin:20px 0;"><h3>Errores:</h3><ul>';
        foreach (session('detalles_errores') as $error) {
            $listaErrores .= '<li>' . $error . '</li>';
        }
        $listaErrores .= '</ul></div>';
    }

    // --- NUEVO: LISTA DE ACTUALIZADOS ---
    $listaActualizados = '';
    if (session('detalles_actualizados')) {
        $listaActualizados = '
        <div style="color:#055160; background:#cff4fc; border:1px solid #b6effb; padding:15px; margin:20px 0; max-height:200px; overflow-y:auto;">
            <h3 style="margin-top:0">📋 Lista de Alumnos Actualizados:</h3>
            <ul style="font-size:12px;">';
        foreach (session('detalles_actualizados') as $alumno) {
            $listaActualizados .= '<li>' . $alumno . '</li>';
        }
        $listaActualizados .= '</ul></div>';
    }
    // ------------------------------------

    return '
        <div style="font-family:sans-serif; max-width:800px; margin:40px auto; padding:30px; border:1px solid #eee; border-radius:10px;">
            <h1 style="color:#2c3e50;">Pestaña 3: Importar Alumnos Actuales (2025)</h1>
            ' . (session('success') ? '<div style="background:#d1e7dd; color:#0f5132; padding:15px; margin-bottom:20px;">' . session('success') . '</div>' : '') . '

            ' . $listaErrores . '
            ' . $listaActualizados . ' <!-- AQUÍ SE MUESTRA LA LISTA -->

            <form action="' . route('import.active_students') . '" method="POST" enctype="multipart/form-data">
                ' . csrf_field() . '
                <p>Selecciona el Excel que contiene los datos socioeconómicos y de matrícula 2025:</p>
                <input type="file" name="file" required><br><br>
                <button type="submit" style="background:#2980b9; color:white; padding:12px 25px; border:none; cursor:pointer; font-weight:bold;">
                    🚀 PROCESAR ALUMNOS 2025
                </button>
            </form>
        </div>
    ';
});
