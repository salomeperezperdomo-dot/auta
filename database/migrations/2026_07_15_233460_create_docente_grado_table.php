<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla pivote real: un docente puede dar clase en varios grados,
     * y un grado puede tener varios docentes. Esta es la única relación
     * del proyecto que de verdad es muchos-a-muchos (a diferencia de
     * asistencia<->estudiantes, que es uno-a-muchos).
     */
    public function up(): void
    {
        Schema::create('docente_grado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('grado_id')->constrained('grados')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'grado_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente_grado');
    }
};
