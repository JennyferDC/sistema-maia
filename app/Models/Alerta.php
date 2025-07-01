<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;

    protected $table = 'alertas';

    protected $fillable = [
        'tipo_alerta',
        'descripcion',
        'resuelta',
        'fecha_generada',
        'nino_id',
        'registro_observacion_salud_id',
        'prediccion_id'
    ];

    // Relaciones

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }

    public function observacionSalud()
    {
        return $this->belongsTo(RegistroObservacionSalud::class, 'registro_observacion_salud_id');
    }

    public function prediccion()
    {
        return $this->belongsTo(Prediccion::class, 'prediccion_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'alerta_id');
    }
}