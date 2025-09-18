<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;
class RolSeeder extends Seeder
{
    public function run()
    {
        Rol::create(['Nombre_rol'=>'Administrador']);
        Rol::create(['Nombre_rol'=>'Profesor']);
        Rol::create(['Nombre_rol'=>'Tutor']);
        Rol::create(['Nombre_rol'=>'Estudiante']);
        Rol::create(['Nombre_rol'=>'Prospecto']);

    }
}
