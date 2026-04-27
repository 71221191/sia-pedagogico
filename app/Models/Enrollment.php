<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'person_id',
        'academic_period_id',
        'study_plan_id',
        'cycle',
        'shift_id',
        'section_label',
        'enrollment_type_id',
        'approval_resolution',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function studyPlan()
    {
        return $this->belongsTo(StudyPlan::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function details()
    {
        return $this->hasMany(EnrollmentDetail::class);
    }
}
