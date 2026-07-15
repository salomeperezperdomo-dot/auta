<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    public $timestamps = false;
    protected $fillable = ['usuario_id', 'accion', 'tabla_afectada', 'registro_id', 'detalle', 'fecha_hora'];

    // Un log pertenece a un usuario
    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }
}