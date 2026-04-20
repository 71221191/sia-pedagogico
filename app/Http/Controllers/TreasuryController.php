<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class TreasuryController extends Controller
{
    public function index(Request $request)
    {
        // Filtramos por estado (por defecto pendientes)
        $status = $request->input('status', 'pending');

        $payments = Payment::with('person') // Traemos al alumno
            ->where('status', $status)
            ->orderBy('created_at', 'asc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Treasury/Index', [
            'payments' => $payments,
            'currentStatus' => $status
        ]);
    }

    public function verify(Request $request, \App\Models\Payment $payment)
    {
        // 1. VALIDACIÓN: Ahora incluimos el archivo 'official_receipt'
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255',
            'official_receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Boleta oficial del instituto
        ], [
            'official_receipt.mimes' => 'El recibo oficial debe ser PDF o Imagen.',
            'rejection_reason.required_if' => 'Debe explicar el motivo del rechazo.'
        ]);

        $data = [
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            'verified_by' => \Illuminate\Support\Facades\Auth::id(),
            'verified_at' => now(),
        ];

        // 2. LÓGICA DE CANJE: Si el tesorero sube la boleta del otro sistema
        if ($request->hasFile('official_receipt')) {
            // Borramos la boleta anterior si existe (limpieza de disco)
            if ($payment->official_receipt_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($payment->official_receipt_path);
            }

            // Guardamos en: storage/app/public/official_receipts
            $data['official_receipt_path'] = $request->file('official_receipt')->store('official_receipts', 'public');
        }

        // 3. ACTUALIZAMOS EL PAGO
        $payment->update($data);

        // 4. LIMPIEZA DE CACHÉ (Lo que ya tenías, mantenlo siempre)
        $period = \App\Models\AcademicPeriod::where('status', 'open')->first();
        if ($period) {
            \Illuminate\Support\Facades\Cache::forget("eligible_courses_{$payment->person_id}_{$period->id}");
        }

        return redirect()->back()->with('success', 'Operación de tesorería procesada correctamente.');
    }
}
