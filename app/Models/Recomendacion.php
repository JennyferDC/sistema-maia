<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recomendacion extends Model
{
    use HasFactory;

    protected $table = 'recomendaciones';

    protected $fillable = [
        'contenido',
        'fecha_generada',
        'nino_id',
        'registro_observacion_salud_id',
        'diagnostico_medico_id',
        'prediccion_id',
        'alerta_id'
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

    public function diagnosticoMedico()
    {
        return $this->belongsTo(DiagnosticoMedico::class, 'diagnostico_medico_id');
    }

    public function alerta()
    {
        return $this->belongsTo(Alerta::class, 'alerta_id');
    }
}