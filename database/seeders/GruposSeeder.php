<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grado;
use App\Models\Grupo;

class GruposSeeder extends Seeder
{
    public function run()
    {
        // Crea 2 grupos por cada grado (ej: "6°1", "6°2").
        // Es un valor de partida razonable; si algún grado necesita más
        // grupos (o menos), se puede ajustar luego desde el panel.
        $grados = Grado::all();

        foreach ($grados as $grado) {
            foreach ([1, 2] as $numero) {
                Grupo::updateOrCreate(
                    ['grado_id' => $grado->id, 'nombre' => $grado->nombre . $numero],
                    []
                );
            }
        }
    }
}
