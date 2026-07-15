<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grado_id')->constrained('grados'); // Vinculado a grados
            $table->time('hora_entrada');      // Hora de inicio de clases
            $table->time('hora_limite');       // Límite para marcar retardo
            $table->enum('dias', ['Lunes a Viernes', 'Lunes a Sábado'])->default('Lunes a Viernes');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('horarios');
    }
};