<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $fillable = ['grado_id', 'hora_entrada', 'hora_limite', 'dias'];

    // Un horario pertenece a un grado
    public function grado() {
        return $this->belongsTo(Grado::class);
    }
}