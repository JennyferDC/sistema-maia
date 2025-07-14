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
            EtapasDesarrolloSeeder::class,
            HitoSeeder::class,
            NinoSeeder::class,
            HitoLogradoSeeder::class,
        ]);
    }
}
