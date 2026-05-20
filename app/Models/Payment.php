<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'person_id',
        'payment_concept_id', // El vínculo al TUPA
        'concept',           // Nombre manual (por si acaso)
        'amount',            // Monto real pagado
        'operation_number',
        'voucher_image_path',     // El papel del banco (sube Alumno)
        'official_receipt_path',  // La boleta del instituto (sube Tesorero)
        'status',                 // pending, approved, rejected
        'rejection_reason',
        'verified_by',
        'verified_at',
        'paid_at',     // <--- AGREGAR
        'is_imported' // <--- AGREGAR
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Relación con el alumno/personal
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    // Relación con el TUPA (Concepto oficial)
    public function paymentConcept()
    {
        return $this->belongsTo(PaymentConcept::class, 'payment_concept_id');
    }

    // Quién verificó el pago
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
