<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EtapaDesarrollo;
use App\Models\Hito;

class HitoSeeder extends Seeder
{
    public function run(): void
    {
        $recienNacido = EtapaDesarrollo::where('nombre_etapa', 'Recién Nacido (0-1 mes)')->first();
        $lactante = EtapaDesarrollo::where('nombre_etapa', 'Lactante (1-12 meses)')->first();
        $primeraInfancia = EtapaDesarrollo::where('nombre_etapa', 'Primera Infancia (1-2 años)')->first();
        $segundaInfancia = EtapaDesarrollo::where('nombre_etapa', 'Segunda Infancia (2-3 años)')->first();

        // Recién Nacido
        Hito::create(['nombre_hito' => 'Levanta la cabeza', 'etapa_desarrollo_id' => $recienNacido->id]);
        Hito::create(['nombre_hito' => 'Fija la mirada', 'etapa_desarrollo_id' => $recienNacido->id]);
        Hito::create(['nombre_hito' => 'Responde a sonidos fuertes con sobresalto', 'etapa_desarrollo_id' => $recienNacido->id]);
        Hito::create(['nombre_hito' => 'Llora para expresar necesidades', 'etapa_desarrollo_id' => $recienNacido->id]);
        Hito::create(['nombre_hito' => 'Mueve brazos y piernas espontáneamente', 'etapa_desarrollo_id' => $recienNacido->id]);
        Hito::create(['nombre_hito' => 'Presenta reflejos primitivos (succión, prensión, Moro)', 'etapa_desarrollo_id' => $recienNacido->id]);

        // Lactante
        Hito::create(['nombre_hito' => 'Se sienta solo', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Sostiene la cabeza por períodos más largos', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Sigue objetos con la mirada', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Sonríe en respuesta a estímulos', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Balbucea sonidos simples', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Se voltea solo', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Se sienta con apoyo', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Comienza a gatear', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Usa pinza para agarrar objetos', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Reacciona al nombre', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Se pone de pie con apoyo', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Da pasos con ayuda', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Dice palabras simples como "mamá"', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Hace gestos como aplaudir o decir adiós', 'etapa_desarrollo_id' => $lactante->id]);
        Hito::create(['nombre_hito' => 'Entiende órdenes simples', 'etapa_desarrollo_id' => $lactante->id]);

        // Primera Infancia
        Hito::create(['nombre_hito' => 'Camina solo sin apoyo', 'etapa_desarrollo_id' => $primeraInfancia->id]);
        Hito::create(['nombre_hito' => 'Comienza a correr', 'etapa_desarrollo_id' => $primeraInfancia->id]);
        Hito::create(['nombre_hito' => 'Sube escaleras con ayuda', 'etapa_desarrollo_id' => $primeraInfancia->id]);
        Hito::create(['nombre_hito' => 'Dice frases de 2 palabras', 'etapa_desarrollo_id' => $primeraInfancia->id]);
        Hito::create(['nombre_hito' => 'Señala partes del cuerpo', 'etapa_desarrollo_id' => $primeraInfancia->id]);
        Hito::create(['nombre_hito' => 'Usa cucharita con derrames', 'etapa_desarrollo_id' => $primeraInfancia->id]);
        Hito::create(['nombre_hito' => 'Reconoce nombres de personas u objetos', 'etapa_desarrollo_id' => $primeraInfancia->id]);
        Hito::create(['nombre_hito' => 'Realiza juego simbólico simple (dar de comer a muñecos)', 'etapa_desarrollo_id' => $primeraInfancia->id]);

        // Segunda Infancia
        Hito::create(['nombre_hito' => 'Salta con ambos pies', 'etapa_desarrollo_id' => $segundaInfancia->id]);
        Hito::create(['nombre_hito' => 'Sube y baja escaleras alternando pies', 'etapa_desarrollo_id' => $segundaInfancia->id]);
        Hito::create(['nombre_hito' => 'Corre con más coordinación', 'etapa_desarrollo_id' => $segundaInfancia->id]);
        Hito::create(['nombre_hito' => 'Hace frases de 3 o más palabras', 'etapa_desarrollo_id' => $segundaInfancia->id]);
        Hito::create(['nombre_hito' => 'Identifica colores y figuras simples', 'etapa_desarrollo_id' => $segundaInfancia->id]);
        Hito::create(['nombre_hito' => 'Participa en juegos simbólicos', 'etapa_desarrollo_id' => $segundaInfancia->id]);
        Hito::create(['nombre_hito' => 'Nombra emociones básicas', 'etapa_desarrollo_id' => $segundaInfancia->id]);
        Hito::create(['nombre_hito' => 'Se quita algunas prendas sin ayuda', 'etapa_desarrollo_id' => $segundaInfancia->id]);
        Hito::create(['nombre_hito' => 'Controla esfínteres durante el día', 'etapa_desarrollo_id' => $segundaInfancia->id]);
    }
}
