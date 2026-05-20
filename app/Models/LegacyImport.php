<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegacyImport extends Model
{
    // Forzamos el nombre de la tabla que ya tienes en tu DB
    protected $table = 'legacy_imports';

    protected $fillable = [
        'filename',
        'import_type',
        'records_processed',
        'created_count',
        'updated_count',
        'omitted_count',
        'error_count',
        'errors_log',
        'results_details',
        'imported_by'
    ];

    protected $casts = [
        'results_details' => 'array',
        'errors_log' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }
}
