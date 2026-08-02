<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 20);   // Ej: "6°1", "6°2", "7°1"
            $table->foreignId('grado_id')->constrained('grados');
            $table->timestamps();

            // No puede haber dos grupos con el mismo nombre dentro del mismo grado
            $table->unique(['grado_id', 'nombre']);
        });
    }
    public function down() {
        Schema::dropIfExists('grupos');
    }
};
