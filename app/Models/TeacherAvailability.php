<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'day_of_week',
        'time_slot_id',
        'is_available'
    ];

    // Relación con el Profesor (Persona)
    public function teacher()
    {
        return $this->belongsTo(Person::class, 'teacher_id');
    }

    // Relación con el Bloque de Tiempo
    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}
