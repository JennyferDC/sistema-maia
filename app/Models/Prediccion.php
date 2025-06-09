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
        'contenido',
        'probabilidad',
        'fuente_datos',
        'nino_id',
        'fecha',
    ];

    // Relaciones

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }

    public function recomendaciones()
    {
        return $this->hasMany(Recomendacion::class, 'prediccion_id');
    }
}
