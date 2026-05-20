<?php

namespace App\Http\Controllers;

use App\Models\LearningForum;
use App\Models\LearningForumPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ForumController extends Controller
{
    /**
     * Muestra el foro con todos sus mensajes.
     */
    public function show(LearningForum $forum)
    {
        // 1. Cargamos el foro con su unidad, curso y los mensajes (posts)
        // Incluimos al autor de cada mensaje (tabla people) y su usuario (para el avatar)
        $forum->load([
            'unit.section.course',
            'posts.author.user.roles' // <--- AGREGAMOS ".roles" AQUÍ
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return Inertia::render('Student/Forums/Show', [
            'forum' => $forum,
            'currentUser' => $user->load('person')
        ]);
    }

    /**
     * Guarda un nuevo mensaje en el foro.
     */
    public function storePost(Request $request, LearningForum $forum)
    {
        // 2. Validar que el foro esté activo
        if (!$forum->is_active) {
            return back()->with('error', 'Este foro ha sido cerrado por el docente.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:learning_forum_posts,id'
        ]);

        // 3. Crear el mensaje vinculado a la persona (alumno o docente)
        LearningForumPost::create([
            'learning_forum_id' => $forum->id,
            'person_id' => Auth::user()->person->id,
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id']
        ]);

        return back()->with('success', 'Tu intervención ha sido publicada.');
    }
}
