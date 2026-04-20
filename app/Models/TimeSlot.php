<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = ['shift', 'start_time', 'end_time', 'order', 'is_break'];

    // Helper para ver el rango de hora bonito
    public function getRangeAttribute() {
        return date('H:i', strtotime($this->start_time)) . ' - ' . date('H:i', strtotime($this->end_time));
    }
}
