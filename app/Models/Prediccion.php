<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediccion extends Model
{
    use HasFactory;

    protected $table = 'predicciones';

    protected $fillable = [
        'tipo',
        'resultado',
        'probabilidad',
        'fecha_prediccion',
        'nino_id',
        'registro_observacion_salud_id',
        'diagnostico_medico_id'
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

    public function diagnosticoMedico()
    {
        return $this->belongsTo(DiagnosticoMedico::class, 'diagnostico_medico_id');
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class, 'prediccion_id');
    }

    public function recomendaciones()
    {
        return $this->hasMany(Recomendacion::class, 'prediccion_id');
    }
}
