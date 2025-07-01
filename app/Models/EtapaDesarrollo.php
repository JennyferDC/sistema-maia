<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapaDesarrollo extends Model
{
    use HasFactory;

    protected $table = 'etapas_desarrollo'; 

    protected $fillable = ['nombre_etapa']; 

    // Relaciones

    public function ninos()
    {
        return $this->hasMany(Nino::class, 'etapa_desarrollo_id');
    }

    public function hitos()
    {
        return $this->hasMany(Hito::class, 'etapa_desarrollo_id');
    }

    public function evaluaciones()
    {
        return $this->hasMany(EvaluacionDesarrollo::class, 'etapa_desarrollo_id');
    }

    public function fotoNinos()
    {
        return $this->hasMany(FotoNino::class, 'etapa_desarrollo_id');
    }

    public function hitoLogrados()
    {
        return $this->hasMany(HitoLogrado::class, 'etapa_desarrollo_id');
    }

}
