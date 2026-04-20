<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConcept extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',      // La partida (ej: 1.3.2 3.1 7)
        'name',      // El concepto (ej: Matrícula Ordinaria)
        'amount',    // El monto oficial (ej: 150.00)
        'is_active', // Si sigue vigente o no
    ];

    // Relación: Un concepto puede estar en muchos pagos
    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_concept_id');
    }
}
