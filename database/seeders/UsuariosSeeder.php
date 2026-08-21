<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        // La cuenta demo de estudiante queda vinculada a "Ana" (la primera
        // creada por EstudiantesSeeder, codigo EST001), para que "Mi
        // Asistencia" tenga un estudiante real del cual filtrar.
        $estudianteDemo = Estudiante::where('codigo', 'EST001')->first();

        $usuarios = [
            ['usuario' => 'admin',      'name' => 'Administrador', 'email' => 'admin@sanjose.edu.co',      'contrasena' => 'admin123', 'rol' => 'admin',      'estudiante_id' => null],
            ['usuario' => 'docente',    'name' => 'Docente',       'email' => 'docente@sanjose.edu.co',    'contrasena' => 'doc123',   'rol' => 'docente',    'estudiante_id' => null],
            ['usuario' => 'estudiante', 'name' => 'Estudiante',    'email' => 'estudiante@sanjose.edu.co', 'contrasena' => 'est123',   'rol' => 'estudiante', 'estudiante_id' => $estudianteDemo?->id],
        ];

        foreach ($usuarios as $u) {
            User::updateOrCreate(
                ['usuario' => $u['usuario']],
                [
                    'name'          => $u['name'],
                    'email'         => $u['email'],
                    'password'      => Hash::make($u['contrasena']),
                    'rol'           => $u['rol'],
                    'estudiante_id' => $u['estudiante_id'],
                ]
            );
        }

        // La cuenta demo de docente queda asignada a los grados donde hay
        // estudiantes de prueba (9°, 10°, 11°), para que sus reportes no
        // se vean vacíos. sync() es idempotente: no duplica si se repite.
        $docenteDemo = User::where('usuario', 'docente')->first();
        if ($docenteDemo) {
            $gradosAsignados = \App\Models\Grado::whereIn('nombre', ['9°', '10°', '11°'])->pluck('id');
            $docenteDemo->grados()->sync($gradosAsignados);
        }
    }
}
