<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model {
    protected $table = 'estudiantes';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['nombre', 'codigo', 'grado'];

    public function asistencias() {
        return $this->hasMany(Asistencia::class, 'estudiante_id');
    }
}