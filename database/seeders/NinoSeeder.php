<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nino;
use App\Models\User;
use App\Models\EtapaDesarrollo;

class NinoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtén todos los IDs disponibles
        $madres = User::pluck('id')->toArray();
        $etapas = EtapaDesarrollo::pluck('id')->toArray();

        // Valida que existan madres y etapas
        if (empty($madres) || empty($etapas)) {
            $this->command->error('No hay madres o etapas de desarrollo disponibles para asignar a los niños.');
            return;
        }

        // Crear 2 niños para cada madre
        foreach ($madres as $madre_id) {
            for ($i = 1; $i <= 2; $i++) {
                Nino::create([
                    'nombre' => 'Hijo ' . $i . ' de madre ' . $madre_id,
                    'es_prematuro' => rand(0, 1),
                    'semanas_prematuro' => rand(28, 40),
                    'fecha_nacimiento' => now()->subDays(rand(30, 2000))->format('Y-m-d'),
                    'sexo' => ['masculino', 'femenino'][rand(0,1)],
                    'peso' => rand(2500, 4000) / 1000, // peso en kg
                    'talla' => rand(45, 55), // talla en cm
                    'madre_id' => $madre_id,
                    'etapa_desarrollo_id' => $etapas[array_rand($etapas)],
                ]);
            }
        }
    }
}
