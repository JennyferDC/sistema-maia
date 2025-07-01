<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HitoLogrado extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'hito_logrados';

    protected $fillable = [
        'fecha_logro',
        'observaciones',
        'nino_id',
        'hito_id',
        'etapa_desarrollo_id'
    ];

    // Relación
    
    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }

    public function hito()
    {
        return $this->belongsTo(Hito::class, 'hito_id');
    }

    public function etapaDesarrollo()
    {
        return $this->belongsTo(EtapaDesarrollo::class, 'etapa_desarrollo_id');
    }

}
