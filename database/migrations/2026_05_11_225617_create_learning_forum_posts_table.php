<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('learning_forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_forum_id')->constrained()->onDelete('cascade');
            $table->foreignId('person_id')->constrained('people'); // Autor (Docente o Alumno)
            $table->text('content');
            // Opcional: para permitir respuestas a comentarios específicos (hilos)
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('learning_forum_posts');
    }
};
