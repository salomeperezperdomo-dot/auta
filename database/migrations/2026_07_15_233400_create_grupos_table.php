<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grado_id')->constrained('grados');
            $table->unsignedTinyInteger('numero');   // 1, 2, 3... (seleccionado de una lista, nunca escrito a mano)
            $table->string('nombre', 20);            // Se arma solo: nombre del grado + numero (ej: "6°" + "1" = "6°1")
            $table->timestamps();

            // No puede haber dos grupos con el mismo número dentro del mismo grado
            $table->unique(['grado_id', 'numero']);
        });
    }
    public function down() {
        Schema::dropIfExists('grupos');
    }
};
