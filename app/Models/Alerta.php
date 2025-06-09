<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;

    protected $table = 'alertas';

    protected $fillable = [
        'tipo',
        'descripcion',
        'resuelta',
        'fecha',
        'nino_id',
    ];

    // Relaciones

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }

    public function recomendaciones()
    {
        return $this->hasMany(Recomendacion::class, 'alerta_id');
    }
}