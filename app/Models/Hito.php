<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hito extends Model
{
    use HasFactory;

    protected $table = 'hitos'; // Nombre de tabla

    protected $fillable = [
        
        'nombre_hito',
        'etapa_desarrollo_id',
    ];

    public function etapaDesarrollo()
    {
        return $this->belongsTo(EtapaDesarrollo::class, 'etapa_desarrollo_id');
    }

    public function hitoLogrados()
    {
        return $this->hasMany(HitoLogrado::class, 'hito_id');
    }

}
