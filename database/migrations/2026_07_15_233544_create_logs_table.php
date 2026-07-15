<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios'); // Quién hizo la acción
            $table->enum('accion', ['crear', 'editar', 'eliminar', 'login', 'logout']); // Qué hizo
            $table->string('tabla_afectada', 50);   // En qué tabla: asistencia, estudiantes...
            $table->unsignedBigInteger('registro_id')->nullable(); // ID del registro afectado
            $table->text('detalle')->nullable();    // Descripción del cambio
            $table->timestamp('fecha_hora')->useCurrent(); // Cuándo ocurrió
        });
    }
    public function down() {
        Schema::dropIfExists('logs');
    }
};