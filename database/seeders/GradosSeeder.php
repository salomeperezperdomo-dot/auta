<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grado;

class GradosSeeder extends Seeder
{
    public function run()
    {
        $grados = [
            ['nombre' => '1°',  'descripcion' => 'Primero de primaria'],
            ['nombre' => '2°',  'descripcion' => 'Segundo de primaria'],
            ['nombre' => '3°',  'descripcion' => 'Tercero de primaria'],
            ['nombre' => '4°',  'descripcion' => 'Cuarto de primaria'],
            ['nombre' => '5°',  'descripcion' => 'Quinto de primaria'],
            ['nombre' => '6°',  'descripcion' => 'Sexto de bachillerato'],
            ['nombre' => '7°',  'descripcion' => 'Séptimo de bachillerato'],
            ['nombre' => '8°',  'descripcion' => 'Octavo de bachillerato'],
            ['nombre' => '9°',  'descripcion' => 'Noveno de bachillerato'],
            ['nombre' => '10°', 'descripcion' => 'Décimo de bachillerato'],
            ['nombre' => '11°', 'descripcion' => 'Once - grado de graduación'],
        ];

        foreach ($grados as $grado) {
            // updateOrCreate evita duplicar el grado si el seeder se corre más de una vez
            Grado::updateOrCreate(
                ['nombre' => $grado['nombre']],
                ['descripcion' => $grado['descripcion']]
            );
        }
    }
}
