<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 50)->unique();
            $table->string('contrasena', 255);
            $table->enum('rol', ['admin','docente','estudiante']);
            $table->timestamp('creado_en')->useCurrent();
        });
    }
    public function down() {
        Schema::dropIfExists('usuarios');
    }
};
