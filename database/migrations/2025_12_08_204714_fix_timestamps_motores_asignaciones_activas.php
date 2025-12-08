<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar qué columnas de timestamp existen actualmente
        $columns = DB::select("
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'motores_asignaciones_activas'
            AND COLUMN_NAME LIKE '%created_at%' OR COLUMN_NAME LIKE '%updated_at%'
        ");
        
        $hasOldCreatedAt = false;
        $hasOldUpdatedAt = false;
        $hasNewCreatedAt = false;
        $hasNewUpdatedAt = false;
        
        foreach ($columns as $column) {
            if ($column->COLUMN_NAME === 'motores_asignaciones_activascreated_at') {
                $hasOldCreatedAt = true;
            }
            if ($column->COLUMN_NAME === 'motores_asignaciones_activasupdated_at') {
                $hasOldUpdatedAt = true;
            }
            if ($column->COLUMN_NAME === 'created_at') {
                $hasNewCreatedAt = true;
            }
            if ($column->COLUMN_NAME === 'updated_at') {
                $hasNewUpdatedAt = true;
            }
        }
        
        Schema::table('motores_asignaciones_activas', function (Blueprint $table) use (
            $hasOldCreatedAt, 
            $hasOldUpdatedAt, 
            $hasNewCreatedAt, 
            $hasNewUpdatedAt
        ) {
            // Si existen las columnas antiguas, renombrarlas
            if ($hasOldCreatedAt && !$hasNewCreatedAt) {
                DB::statement("
                    ALTER TABLE motores_asignaciones_activas 
                    CHANGE COLUMN motores_asignaciones_activascreated_at created_at TIMESTAMP NULL DEFAULT NULL
                ");
                echo "Columna created_at renombrada exitosamente.\n";
            } elseif (!$hasNewCreatedAt) {
                // Si no existe ninguna, crearla
                $table->timestamp('created_at')->nullable();
                echo "Columna created_at creada.\n";
            }
            
            if ($hasOldUpdatedAt && !$hasNewUpdatedAt) {
                DB::statement("
                    ALTER TABLE motores_asignaciones_activas 
                    CHANGE COLUMN motores_asignaciones_activasupdated_at updated_at TIMESTAMP NULL DEFAULT NULL
                ");
                echo "Columna updated_at renombrada exitosamente.\n";
            } elseif (!$hasNewUpdatedAt) {
                // Si no existe ninguna, crearla
                $table->timestamp('updated_at')->nullable();
                echo "Columna updated_at creada.\n";
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motores_asignaciones_activas', function (Blueprint $table) {
            // Renombrar de vuelta a los nombres antiguos
            DB::statement("
                ALTER TABLE motores_asignaciones_activas 
                CHANGE COLUMN created_at motores_asignaciones_activascreated_at TIMESTAMP NULL DEFAULT NULL
            ");
            
            DB::statement("
                ALTER TABLE motores_asignaciones_activas 
                CHANGE COLUMN updated_at motores_asignaciones_activasupdated_at TIMESTAMP NULL DEFAULT NULL
            ");
        });
    }
};