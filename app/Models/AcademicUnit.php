<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CourseSection;
use App\Models\LearningResource;
use App\Models\LearningForum;
use App\Models\Task;

class AcademicUnit extends Model
{
    protected $fillable = ['course_section_id', 'name', 'order', 'start_date', 'end_date'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(LearningResource::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function forums(): HasMany
    {
        return $this->hasMany(LearningForum::class);
    }
}
