<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoNino extends Model
{
    use HasFactory;

    protected $table = 'foto_ninos';

    protected $fillable = [
        'ruta_foto',
        'fecha_subida',
        'descripcion',
        'nino_id',
        'etapa_desarrollo_id',
    ];

    // Relaciones

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }

    public function etapaDesarrollo()
    {
        return $this->belongsTo(EtapaDesarrollo::class, 'etapa_desarrollo_id');
    }
    
}