<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\PaymentConcept;

class PaymentController extends Controller
{
    public function index()
    {
        $person = Auth::user()->person;

        return Inertia::render('Payments/Index', [
            // 1. Mandamos los pagos con su concepto oficial cargado
            'payments' => Payment::with('paymentConcept')
                ->where('person_id', $person->id)
                ->orderBy('created_at', 'desc')
                ->get(),

            // 2. Mandamos la lista de precios para el selector
            'concepts' => PaymentConcept::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $person = \App\Models\Person::where('user_id', \Illuminate\Support\Facades\Auth::id())->firstOrFail();

        $request->validate([
            'payment_concept_id' => 'required|exists:payment_concepts,id',
            'operation_number'   => 'required|string|unique:payments,operation_number',
            'voucher'            => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // 1. Buscamos el concepto oficial para jalar el nombre y el monto real
        $concept = \App\Models\PaymentConcept::findOrFail($request->payment_concept_id);

        $path = null;
        if ($request->hasFile('voucher')) {
            $path = $request->file('voucher')->store('vouchers', 'public');
        }

        // 2. CREACIÓN DEL REGISTRO CON DATA REAL
        \App\Models\Payment::create([
            'person_id'          => $person->id,
            'payment_concept_id' => $concept->id, // <--- GUARDAMOS EL ID
            'concept'            => $concept->name, // <--- GUARDAMOS EL NOMBRE REAL
            'amount'             => $concept->amount, // <--- GUARDAMOS EL MONTO DEL TUPA
            'operation_number'   => $request->operation_number,
            'voucher_image_path' => $path,
            'status'             => 'pending',
        ]);

        return redirect()->back()->with('success', 'Tu voucher ha sido enviado con éxito.');
    }

    public function update(Request $request, \App\Models\Payment $payment)
    {
        // Solo permitir editar si está pendiente o rechazado
        if ($payment->status === 'approved') {
            return back()->with('error', 'No se puede editar un pago ya aprobado.');
        }

        $request->validate([
            'payment_concept_id' => 'required|exists:payment_concepts,id',
            'operation_number'   => 'required|string|unique:payments,operation_number,' . $payment->id,
            'voucher'            => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $concept = \App\Models\PaymentConcept::findOrFail($request->payment_concept_id);

        $data = [
            'payment_concept_id' => $concept->id,
            'concept'            => $concept->name,
            'amount'             => $concept->amount,
            'operation_number'   => $request->operation_number,
            'status'             => 'pending', // Al editar, vuelve a revisión
        ];

        if ($request->hasFile('voucher')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($payment->voucher_image_path);
            $data['voucher_image_path'] = $request->file('voucher')->store('vouchers', 'public');
        }

        $payment->update($data);

        return back()->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Payment $payment)
    {
        // REGLA DE SEGURIDAD SENIOR: Solo puede borrar si está PENDIENTE
        if ($payment->status !== 'pending') {
            return back()->with('error', 'No puedes eliminar un pago que ya está en revisión o aprobado.');
        }

        $payment->delete(); // Gracias a SoftDeletes, el registro se oculta pero queda en la BD
        return back()->with('success', 'Registro de pago eliminado.');
    }
}
