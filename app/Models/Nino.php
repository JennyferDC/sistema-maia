<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nino extends Model
{
    use HasFactory;

    protected $table = 'ninos';

    protected $fillable = [
        'nombre',
        'semanas_prematuro',
        'fecha_nacimiento',
        'sexo',
        'peso_nacimiento',
        'talla_nacimiento',
        'madre_id',
        'etapa_desarrollo_id', // Asigna automáticamente
    ];

    // Relaciones
    public function madre()
    {
        return $this->belongsTo(User::class, 'madre_id');
    }

    public function etapaDesarrollo()
    {
        return $this->belongsTo(EtapaDesarrollo::class, 'etapa_desarrollo_id');
    }

    public function hitosLogrados()
    {
        return $this->hasMany(HitoLogrado::class, 'nino_id');
    }

    public function diagnosticoNacimiento()
    {
        return $this->belongsTo(DiagnosticoMedico::class, 'diagnostico_nacimiento_id');
    }

    public function fotos()
    {
        return $this->hasMany(FotoNino::class, 'nino_id');
    }

    public function evaluaciones()
    {
        return $this->hasMany(EvaluacionDesarrollo::class, 'nino_id');
    }

    public function observacionesSalud()
    {
        return $this->hasMany(RegistroObservacionSalud::class, 'nino_id');
    }

    public function diagnosticos()
    {
        return $this->hasMany(DiagnosticoMedico::class, 'nino_id');
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class, 'nino_id');
    }

    public function predicciones()
    {
        return $this->hasMany(Prediccion::class, 'nino_id');
    }

    public function recomendaciones()
    {
        return $this->hasMany(Recomendacion::class, 'nino_id');
    }

    public function talleres()
    {
        return $this->hasMany(TallerActividad::class, 'madre_id');
    }
}
