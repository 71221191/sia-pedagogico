<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $profileService;

    // Inyectamos el servicio que tiene la lógica pesada
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function __invoke()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $person = $user->person;

        $data = [
            'status' => 'success'
        ];

        // Solo si es estudiante, cargamos su "Legajo Digital" sincronizado
        if ($user->hasRole('estudiante') && $person) {
            $data['studentProfile'] = $this->profileService->getStudentDashboardData($person);
        }
        elseif ($user->hasRole('docente') && $person) {
            $data['teacherProfile'] = $this->profileService->getTeacherDashboardData($person);
        }

        return Inertia::render('Dashboard', $data);
    }
}
