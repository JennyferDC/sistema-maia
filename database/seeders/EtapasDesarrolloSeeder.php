<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ¡Falta esta línea!

class EtapasDesarrolloSeeder extends Seeder
{
    public function run()
    {
        DB::table('etapas_desarrollo')->insert([
            ['nombre_etapa' => 'Recién Nacido (0-1 mes)'], 
            ['nombre_etapa' => 'Lactante (1-12 meses)'],
            ['nombre_etapa' => 'Primera Infancia (1-2 años)'],
            ['nombre_etapa' => 'Segunda Infancia (2-3 años)']
        ]);
    }
}
