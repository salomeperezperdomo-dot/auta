<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Orden importa: Grados antes que Grupos, y ambos antes que
     * Estudiantes (porque grado_id y grupo_id son llaves reales).
     * Todos los seeders usan updateOrCreate, así que correr
     * "php artisan db:seed" varias veces no duplica nada.
     */
    public function run(): void
    {
        $this->call([
            GradosSeeder::class,
            GruposSeeder::class,
            EstudiantesSeeder::class,
            UsuariosSeeder::class,
        ]);
    }
}
