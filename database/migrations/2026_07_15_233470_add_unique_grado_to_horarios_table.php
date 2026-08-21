<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Un grado solo puede tener UN horario (la relación Grado::horario()
     * es hasOne). Sin esta restricción, nada impedía crear dos horarios
     * para el mismo grado, y Eloquent elegiría uno de forma arbitraria
     * para calcular los retardos, sin avisar.
     */
    public function up(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->unique('grado_id');
        });
    }

    public function down(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->dropUnique(['grado_id']);
        });
    }
};
