<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Solo se llena cuando rol = 'estudiante': conecta la cuenta de
            // login con SU fila real en la tabla estudiantes. Sin esto no hay
            // forma de saber "cuál estudiante" inició sesión, y la vista
            // "Mi Asistencia" no puede filtrar de verdad del lado del servidor.
            $table->foreignId('estudiante_id')->nullable()->after('rol')
                  ->constrained('estudiantes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['estudiante_id']);
            $table->dropColumn('estudiante_id');
        });
    }
};
