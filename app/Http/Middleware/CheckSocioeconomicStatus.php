<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Person;
use App\Models\AcademicPeriod;
use App\Models\SocioeconomicFile;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log; // ¡Añadir esta línea!

class CheckSocioeconomicStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Auth::check() || !$user->hasRole('estudiante')) {
            return $next($request);
        }

        $person = $user->person;
        $currentPeriod = AcademicPeriod::where('status', 'open')->first();

        // Si no hay periodo abierto, no podemos validar fichas, dejamos pasar.
        if (!$currentPeriod) {
            return $next($request);
        }

        // Buscamos la ficha del alumno para EL PERIODO ACTUAL
        $socioeconomicFile = SocioeconomicFile::where('person_id', $person->id)
                                                ->where('academic_period_id', $currentPeriod->id)
                                                ->first();

        $isFichaValidated = $socioeconomicFile && $socioeconomicFile->is_validated;

        // --- REGLA DE ORO ---
        // Si la ficha NO está validada y el alumno intenta entrar a otra cosa (ej: Matrícula)
        if (!$isFichaValidated && !$request->routeIs('socioeconomic.*')) {
            return redirect()->route('socioeconomic.create')
                ->with('warning', 'Debes completar tu ficha socioeconómica para el periodo ' . $currentPeriod->name);
        }

        // ¡BORRA EL dd()! El flujo debe continuar siempre aquí:
        return $next($request);
    }
}
