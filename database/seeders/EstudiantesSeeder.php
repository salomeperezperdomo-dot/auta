<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;

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
            Estudiante::create($estudiante);
        }
    }
}