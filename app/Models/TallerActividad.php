<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TallerActividad extends Model
{
    use HasFactory;

    protected $table = 'talleres_actividades';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha',
        'ubicacion',
        'madre_id',
    ];

    // Relaciones

    public function madre()
    {
        return $this->belongsTo(User::class, 'madre_id');
    }
}