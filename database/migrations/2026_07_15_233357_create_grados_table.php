<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('grados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 20);         // Ej: "6°", "7°", "11°"
            $table->string('descripcion', 100)->nullable(); // Ej: "Sexto grado"
            $table->integer('num_estudiantes')->default(0); // Contador referencial
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('grados');
    }
};