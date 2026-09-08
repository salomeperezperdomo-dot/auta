<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IntentoAsistencia extends Model {
    protected $table = 'intentos_asistencia';
    protected $fillable = ['codigo', 'estudiante_id', 'resultado', 'fecha', 'hora'];

    public function estudiante() {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }
}
