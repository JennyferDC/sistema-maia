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
        'mensaje',
        'leida',
        'fecha',
        'estado',
        'madre_id',
        'alerta_id',
        'taller_actividad_id'
    ];

    // Relaciones

    public function madre()
    {
        return $this->belongsTo(User::class, 'madre_id');
    }

    public function alerta()
    {
        return $this->belongsTo(Alerta::class, 'alerta_id');
    }

    public function tallerActividad()
    {
        return $this->belongsTo(TallerActividad::class, 'taller_actividad_id');
    }
}
