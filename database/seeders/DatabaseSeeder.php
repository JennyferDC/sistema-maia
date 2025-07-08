<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear usuarios (madres) usando el seeder personalizado
        $this->call([
            UserSeeder::class,
        ]);

        // 2. Crear etapas de desarrollo
        $this->call([
            EtapasDesarrolloSeeder::class,
        ]);

        // 3. Crear hitos (relacionados con las etapas)
        $this->call([
            HitoSeeder::class,
        ]);

        // 4. Crear niños (relacionados con usuarios y etapas)
        $this->call([
            NinoSeeder::class,
        ]);
    }
}
