<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model {
    protected $table = 'asistencia';
    public $timestamps = false;
    protected $fillable = ['estudiante_id', 'nombre', 'grado', 'estado', 'fecha', 'hora'];

    public function estudiante() {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }
}