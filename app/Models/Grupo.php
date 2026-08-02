<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $fillable = ['nombre', 'grado_id'];

    // Un grupo pertenece a un grado (ej: el grupo "6°1" pertenece al grado "6°")
    public function grado() {
        return $this->belongsTo(Grado::class);
    }

    // Un grupo tiene muchos estudiantes
    public function estudiantes() {
        return $this->hasMany(Estudiante::class);
    }
}
