<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'titulo',
        'contenido',
        'fecha',
        'estado',
        'madre_id',
        'alerta_id',
        'evento_id',
    ];

    // Relaciones

    public function madre()
    {
        return $this->belongsTo(Usuario::class, 'madre_id');
    }

    public function alerta()
    {
        return $this->belongsTo(Alerta::class, 'alerta_id');
    }

    public function evento()
    {
        return $this->belongsTo(TallerActividad::class, 'evento_id');
    }
}
