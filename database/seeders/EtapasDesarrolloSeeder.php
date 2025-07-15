<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtapasDesarrolloSeeder extends Seeder
{
    public function run()
    {
        DB::table('etapas_desarrollo')->insert([
            ['nombre_etapa' => 'Recién Nacido (0-1 mes)', 'mes_inicio' => 0, 'mes_fin' => 1],
            ['nombre_etapa' => 'Lactante (1-12 meses)', 'mes_inicio' => 1, 'mes_fin' => 12],
            ['nombre_etapa' => 'Primera Infancia (1-2 años)', 'mes_inicio' => 12, 'mes_fin' => 24],
            ['nombre_etapa' => 'Segunda Infancia (2-3 años)', 'mes_inicio' => 24, 'mes_fin' => 36]
        ]);
    }
}
