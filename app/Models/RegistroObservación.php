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
        'descripcion',
        'fecha',
        'nino_id',
    ];

    // Relaciones

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }
}