<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\TaskSubmission;

class Task extends Model
{
    protected $fillable = [
        'academic_unit_id', 'title', 'description', 'due_date',
        'closing_date', 'max_score', 'allowed_formats', 'max_file_size_kb'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'closing_date' => 'datetime',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(AcademicUnit::class, 'academic_unit_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
