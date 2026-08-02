<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        $usuarios = [
            ['usuario' => 'admin',      'name' => 'Administrador', 'email' => 'admin@sanjose.edu.co',      'contrasena' => 'admin123', 'rol' => 'admin'],
            ['usuario' => 'docente',    'name' => 'Docente',       'email' => 'docente@sanjose.edu.co',    'contrasena' => 'doc123',   'rol' => 'docente'],
            ['usuario' => 'estudiante', 'name' => 'Estudiante',    'email' => 'estudiante@sanjose.edu.co', 'contrasena' => 'est123',   'rol' => 'estudiante'],
        ];

        foreach ($usuarios as $u) {
            User::updateOrCreate(
                ['usuario' => $u['usuario']],
                [
                    'name'     => $u['name'],
                    'email'    => $u['email'],
                    'password' => Hash::make($u['contrasena']),
                    'rol'      => $u['rol'],
                ]
            );
        }
    }
}
