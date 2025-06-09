<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionDesarrollo extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones_desarrollo'; // Nombre de tabla

    protected $fillable = [
        'fecha',
        'peso',
        'talla',
        'nino_id',
        'etapa_desarrollo_id',
    ];
   
    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }

    public function etapaDesarrollo()
    {
        return $this->belongsTo(EtapaDesarrollo::class, 'etapa_desarrollo_id');
    }
    
}
