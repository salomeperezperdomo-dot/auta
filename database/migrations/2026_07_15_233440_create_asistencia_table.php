<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes');
            $table->string('nombre', 100);
            $table->string('grado', 10);
            $table->enum('estado', ['Presente','Ausente','Retardo']);
            $table->date('fecha');
            $table->time('hora');
            $table->timestamp('registrado_en')->useCurrent();
        });
    }
    public function down() {
        Schema::dropIfExists('asistencia');
    }
};
