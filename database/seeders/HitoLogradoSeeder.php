<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nino;
use App\Models\Hito;
use App\Models\HitoLogrado;
use App\Models\EtapaDesarrollo;

class HitoLogradoSeeder extends Seeder
{
    public function run(): void
    {
        $ninos = Nino::all();
        $etapas = EtapaDesarrollo::with('hitos')->get();

        foreach ($ninos as $nino) {
            // Selecciona una etapa aleatoria para el niño
            $etapasDisponibles = $etapas->filter(fn($e) => $e->hitos->count() > 0);
            $numHitos = rand(2, 5);
            $hitosLogrados = collect();

            // Selecciona hitos aleatorios de cualquier etapa
            $todosHitos = $etapasDisponibles->flatMap(fn($e) => $e->hitos);
            $hitosSeleccionados = $todosHitos->random(min($numHitos, $todosHitos->count()));
            if (!$hitosSeleccionados instanceof \Illuminate\Support\Collection) {
                $hitosSeleccionados = collect([$hitosSeleccionados]);
            }

            foreach ($hitosSeleccionados as $hito) {
                HitoLogrado::create([
                    'fecha_logro' => now()->subDays(rand(1, 1000)),
                    'observaciones' => 'Logro automático de prueba',
                    'nino_id' => $nino->id,
                    'hito_id' => $hito->id,
                    'etapa_desarrollo_id' => $hito->etapa_desarrollo_id,
                ]);
            }
        }
    }
}
