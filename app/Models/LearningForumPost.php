<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningForumPost extends Model {
    protected $fillable = ['learning_forum_id', 'person_id', 'content', 'parent_id'];

    public function author(): BelongsTo {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function forum(): BelongsTo {
        return $this->belongsTo(LearningForum::class, 'learning_forum_id');
    }
}
