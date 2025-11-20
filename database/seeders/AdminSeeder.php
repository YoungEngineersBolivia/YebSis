<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Creamos la persona asociada al administrador.
        $personaId = DB::table('personas')->insertGetId([
            'Nombre' => 'Admin',
            'Apellido' => 'User',
            'Genero' => 'Indefinido',
            'Direccion_domicilio' => 'Dirección de Admin',
            'Fecha_nacimiento' => '1990-01-01',
            'Fecha_registro' => Carbon::now(),
            'Celular' => '1234567890',
            'Id_roles' => 1, // Asignamos directamente el ID del rol de administrador.
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. Creamos el usuario administrador.
        DB::table('usuarios')->insert([
            'Correo' => 'admin@superadmin.com',
            'Contrasenia' => Hash::make('yebolivia1234'), // ¡Recuerda cambiar 'password' por una contraseña segura!
            'Id_personas' => $personaId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}