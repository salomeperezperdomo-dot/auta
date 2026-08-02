<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\Grupo;

class EstudiantesSeeder extends Seeder
{
    public function run()
    {
        $estudiantes = [
            ['nombre' => 'Ana', 'grado' => '10°', 'codigo' => 'EST001'],
            ['nombre' => 'Luis', 'grado' => '10°', 'codigo' => 'EST002'],
            ['nombre' => 'Carlos', 'grado' => '11°', 'codigo' => 'EST003'],
            ['nombre' => 'María', 'grado' => '11°', 'codigo' => 'EST004'],
            ['nombre' => 'Laura', 'grado' => '9°', 'codigo' => 'EST005'],
        ];

        foreach ($estudiantes as $estudiante) {
            $grado = Grado::where('nombre', $estudiante['grado'])->first();
            if (!$grado) {
                continue; // el grado debe existir (lo crea GradosSeeder antes que este)
            }

            // Los reparte en el primer grupo de su grado (ej: "10°1")
            $grupo = Grupo::where('grado_id', $grado->id)->orderBy('nombre')->first();

            Estudiante::updateOrCreate(
                ['codigo' => $estudiante['codigo']],
                [
                    'nombre'   => $estudiante['nombre'],
                    'grado_id' => $grado->id,
                    'grupo_id' => $grupo?->id,
                ]
            );
        }
    }
}
