<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';
    protected $fillable = ['nombre', 'descripcion', 'num_estudiantes'];

    // Un grado tiene muchos estudiantes
    public function estudiantes() {
        return $this->hasMany(Estudiante::class);
    }

    // Un grado tiene muchos grupos (ej: el grado "6°" tiene los grupos "6°1", "6°2"...)
    public function grupos() {
        return $this->hasMany(Grupo::class);
    }

    // Un grado tiene un horario
    public function horario() {
        return $this->hasOne(Horario::class);
    }
}