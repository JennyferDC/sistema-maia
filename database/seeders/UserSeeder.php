<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear usuarios de prueba (madres)
        $users = [
            [
                'name' => 'María',
                'apellido' => 'González',
                'email' => 'maria.gonzalez@example.com',
                'telefono' => '3001234567',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ana',
                'apellido' => 'Rodríguez',
                'email' => 'ana.rodriguez@example.com',
                'telefono' => '3002345678',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carmen',
                'apellido' => 'López',
                'email' => 'carmen.lopez@example.com',
                'telefono' => '3003456789',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sofia',
                'apellido' => 'Martínez',
                'email' => 'sofia.martinez@example.com',
                'telefono' => '3004567890',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Isabella',
                'apellido' => 'Hernández',
                'email' => 'isabella.hernandez@example.com',
                'telefono' => '3005678901',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Crear usuarios adicionales usando factory si existe
        if (class_exists(\Database\Factories\UserFactory::class)) {
            User::factory(10)->create();
        }
    }
} 