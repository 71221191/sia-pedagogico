<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IdentityController extends Controller
{
    public function uploadPhoto(Request $request, Person $person)
    {
        // 1. Validar que sea una imagen real y no muy pesada
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
        ]);

        // 2. Si ya tenía una foto, la borramos del servidor para no gastar espacio
        if ($person->official_photo_path) {
            Storage::disk('public')->delete($person->official_photo_path);
        }

        // 3. Guardamos la nueva foto en la carpeta 'photos' del disco público
        $path = $request->file('photo')->store('photos', 'public');

        // 4. Actualizamos la base de datos
        $person->update([
            'official_photo_path' => $path
        ]);

        return back()->with('success', 'Foto oficial actualizada correctamente.');
    }
}
