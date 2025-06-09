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
        'nino_id',
        'hito_id',
    ];

    // Relación: un hito logrado pertenece a un niño
    
    public function nino()
    {
        return $this->belongsTo(Nino::class, 'nino_id');
    }

    public function hito()
    {
        return $this->belongsTo(Hito::class, 'hito_id');
    }

}
