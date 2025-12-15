<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RespuestasSeeder extends Seeder
{
    /**
     * Seed the respuestas table with predefined answers.
     *
     * @return void
     */
    public function run()
    {
        // Verificar si ya existen las respuestas
        if (DB::table('respuestas')->count() > 0) {
            $this->command->info('Las respuestas ya existen. Saltando seeder.');
            return;
        }

        DB::table('respuestas')->insert([
            [
                'Id_respuestas' => 1,
                'Respuesta' => 'Sí',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Id_respuestas' => 2,
                'Respuesta' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Id_respuestas' => 3,
                'Respuesta' => 'En proceso',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Respuestas creadas exitosamente: Sí, No, En proceso');
    }
}
