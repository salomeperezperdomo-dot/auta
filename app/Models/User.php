<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'usuario',
        'email',
        'password',
        'rol',
        'estudiante_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Solo tiene valor cuando rol = 'estudiante': es SU fila real en la
    // tabla estudiantes, la que permite filtrar "Mi Asistencia" en el servidor.
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    // Solo aplica cuando rol = 'docente': los grados en los que da clase.
    // Es una relación muchos-a-muchos real (un docente puede tener varios
    // grados, un grado puede tener varios docentes), por eso usa la tabla
    // pivote docente_grado en vez de una llave foránea directa.
    public function grados()
    {
        return $this->belongsToMany(Grado::class, 'docente_grado');
    }
}
