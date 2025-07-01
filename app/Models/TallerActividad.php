<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TallerActividad extends Model
{
    use HasFactory;

    protected $table = 'talleres_actividades';

    protected $fillable = [
        'titulo',
        'tipo_evento',
        'descripcion',
        'fecha_evento',
        'nino_id',
        'madre_id'
    ];

    // Relaciones

    public function madre()
    {
        return $this->belongsTo(User::class, 'madre_id');
    }

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'taller_actividad_id');
    }
}