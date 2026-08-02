<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model {
    protected $table = 'estudiantes';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre', 'codigo', 'grado_id', 'grupo_id'];

    // Cada estudiante pertenece a un grado (ej: "6°")
    public function grado() {
        return $this->belongsTo(Grado::class);
    }

    // Cada estudiante pertenece a un grupo (ej: "6°1")
    public function grupo() {
        return $this->belongsTo(Grupo::class);
    }

    public function asistencias() {
        return $this->hasMany(Asistencia::class, 'estudiante_id');
    }
}
