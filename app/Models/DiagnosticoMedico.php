<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosticoMedico extends Model
{
    use HasFactory;

    protected $table = 'diagnosticos_medicos';

    protected $fillable = [
        'diagnostico',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'nino_id',
    ];

    // Relaciones

    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }
}
