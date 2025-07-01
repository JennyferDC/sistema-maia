<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroObservacionSalud extends Model
{
    use HasFactory;

    protected $table = 'registros_observacion_salud';

    protected $fillable = [
        'tipo_observacion',
        'observaciones',
        'fecha_registro',
        'nino_id',
    ];

    // Relaciones

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class, 'registro_observacion_salud_id');
    }

    public function predicciones()
    {
        return $this->hasMany(Prediccion::class, 'registro_observacion_salud_id');
    }

    public function recomendaciones()
    {
        return $this->hasMany(Recomendacion::class, 'registro_observacion_salud_id');
    }
}