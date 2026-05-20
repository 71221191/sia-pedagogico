<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningResource extends Model
{
    protected $fillable = ['academic_unit_id', 'type', 'title', 'description', 'file_path', 'url', 'is_visible'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(AcademicUnit::class, 'academic_unit_id');
    }
}
