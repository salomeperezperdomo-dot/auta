<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabla de auditoría para detectar patrones de mal uso del carnet/QR
// (observación del profe: "evitar que una persona utilice el QR de otra").
//
// No reemplaza la tabla `asistencia` (que solo guarda registros válidos).
// Esta guarda TODOS los intentos de escaneo que no resultaron en un
// registro válido, para poder revisar después si, por ejemplo, el mismo
// código se intentó usar varias veces el mismo día desde el celular de
// alguien (bloqueado por el filtro de calidad) o ya estaba duplicado.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intentos_asistencia', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50);
            $table->foreignId('estudiante_id')->nullable()->constrained('estudiantes')->nullOnDelete();
            // 'duplicado'          -> el estudiante ya tenía asistencia registrada hoy
            // 'no_encontrado'      -> el código no corresponde a ningún estudiante
            // 'pantalla_rechazada' -> el filtro de calidad detectó que el QR se mostraba desde una pantalla
            $table->string('resultado', 30);
            $table->date('fecha');
            $table->time('hora');
            $table->timestamps();

            $table->index(['codigo', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intentos_asistencia');
    }
};
