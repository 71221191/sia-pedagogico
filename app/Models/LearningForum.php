<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningForum extends Model
{
    protected $fillable = ['academic_unit_id', 'title', 'description', 'is_active'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(AcademicUnit::class, 'academic_unit_id');
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LearningForumPost::class)->orderBy('created_at', 'asc');
    }
}
