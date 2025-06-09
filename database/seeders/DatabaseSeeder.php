<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Primero crea los usuarios (madres)
        User::factory(10)->create();
        
        User::factory()->create([
            'name' => 'Test User',
            'apellido' => 'Un apellido',
            'email' => 'test@example.com',
        ]);

        // 2. Luego las etapas de desarrollo
        $this->call([
            EtapasDesarrolloSeeder::class,
        ]);

        // 3. Finalmente los niños (que dependen de los anteriores)
        $this->call([
            NinoSeeder::class,
        ]);
    }
}
