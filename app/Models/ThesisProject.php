<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth; // <--- 1. ESTA SOLUCIONA LO DE 'AUTH'
use Inertia\Inertia;                // <--- 2. ESTA SOLUCIONA LO DE 'INERTIA'
use Illuminate\Database\Eloquent\Model;

class ThesisProject extends Model
{
    const STATUS_REGISTERED = 'registered'; // Recién creada por alumno
    const STATUS_ASSIGNED   = 'assigned';   // Tiene Asesor y Jurados
    const STATUS_SCHEDULED  = 'scheduled';  // Tiene Fecha y Hora
    const STATUS_DEFENDED   = 'defended';   // Ya tiene notas
    const STATUS_CLOSED     = 'closed';     // Expediente finalizado (bloqueado)

    protected $fillable = [
        'title',
        'research_line',
        'type_of_research', // Nuevo
        'promotion_year',   // Nuevo
        'specialty_resolution', // Nuevo
        'document_correlative', // Nuevo
        'advisor_id',
        'status',
        'is_imported',
        'scheduled_date',
        'scheduled_time',
        'scheduled_location',
        'office_number'
    ];

    protected $appends = [
        'auto_promotion',
        'auto_resolution',
        'full_oficio_number'
    ];

    // Relación con los autores (Alumnos)
    public function authors() {
        return $this->belongsToMany(Person::class, 'thesis_authors', 'thesis_project_id', 'student_id');
    }

    public function index()
    {
        $person = Auth::user()->person;

        // AÑADIMOS 'defenseAct' al load para traer la nota y el resultado
        $myProject = $person->thesisProjects()
            ->with(['authors', 'advisor', 'jurors.teacher', 'defenseAct'])
            ->first();

        return Inertia::render('Student/Thesis/Index', [
            'project' => $myProject
        ]);
    }

    public function getAutoPromotionAttribute()
    {
        // 1. Obtenemos el primer tesista (Anderson)
        $firstAuthor = $this->authors->first();

        if ($firstAuthor) {
            // 2. Buscamos su última matrícula (la que ya cargamos en el controlador)
            $lastEnrollment = $firstAuthor->enrollments->last();

            if ($lastEnrollment && $lastEnrollment->academicPeriod) {
                // 3. Devolvemos el nombre que vimos en el debug ("2026-I")
                return $lastEnrollment->academicPeriod->name;
            }
        }

        return 'Sin Matrícula';
    }

    public function getAutoResolutionAttribute()
    {
        // Si el proyecto fue importado y ya tiene una resolución manual, la respetamos
        if ($this->specialty_resolution) {
            return $this->specialty_resolution;
        }

        // Si no, buscamos al primer autor (tesista)
        $firstAuthor = $this->authors->first();

        if ($firstAuthor) {
            // Buscamos su matrícula para llegar al Plan de Estudios
            // Asumiendo que la relación es authors -> enrollments -> studyPlan
            $enrollment = $firstAuthor->enrollments()->latest()->first();
            if ($enrollment && $enrollment->studyPlan) {
                return $enrollment->studyPlan->resolution_code;
            }
        }

        return 'SIN RESOLUCIÓN DEFINIDA';
    }

    // 3. Formatear el Oficio automáticamente (Ej: 001-2026)
    public function getFullOficioNumberAttribute()
    {
        if (!$this->office_number) return "PENDIENTE";
        return str_pad($this->office_number, 3, '0', STR_PAD_LEFT) . '-' . $this->created_at->format('Y');
    }

    // Relación con el Asesor (Docente)
    public function advisor() {
        return $this->belongsTo(Person::class, 'advisor_id');
    }

    // Relación con los Jurados
    public function jurors() {
        return $this->hasMany(ThesisJuror::class);
    }

    public function documents()
    {
        // Asegúrate de que el nombre sea ThesisDocument (con S y con U)
        return $this->hasMany(ThesisDocument::class);
    }

    public function defenseAct()
    {
        return $this->hasOne(DefenseAct::class);
    }
}
