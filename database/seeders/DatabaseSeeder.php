<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Orden de ejecución importante
        $this->call([
            RolSeeder::class,        // 1. Roles y Permisos (Base)
            AdminSeeder::class,      // 2. Usuario Administrador (Depende de Roles)
            RespuestasSeeder::class, // 3. Respuestas Predefinidas (Datos paramétricos)
        ]);
    }
}
