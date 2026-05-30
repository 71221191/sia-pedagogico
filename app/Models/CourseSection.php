<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'academic_period_id',
        'shift_id',
        'name',
        'teacher_id',
        'vacancy_limit',
        'is_closed',
        'acta_number',
        'acta_close_date',
        'syllabus_path',
        'syllabus_name'
    ];

    protected $casts = [
        'is_closed' => 'boolean',
        'acta_close_date' => 'datetime',
    ];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    // El docente apunta a Person (ID 2578)
    public function teacher()
    {
        return $this->belongsTo(Person::class, 'teacher_id');
    }

    public function enrollmentDetails()
    {
        return $this->hasMany(EnrollmentDetail::class, 'course_section_id');
    }

    /**
     * Obtener el turno asociado a esta sección.
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
