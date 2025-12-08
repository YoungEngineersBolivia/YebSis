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
        // PASO 1: Obtener todas las claves foráneas que usan el índice
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'motores_asignaciones_activas' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        // Guardar información de las claves foráneas para recrearlas después
        $foreignKeyInfo = [];
        foreach ($foreignKeys as $fk) {
            $details = DB::select("
                SELECT 
                    kcu.COLUMN_NAME,
                    kcu.REFERENCED_TABLE_NAME,
                    kcu.REFERENCED_COLUMN_NAME,
                    rc.UPDATE_RULE,
                    rc.DELETE_RULE
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
                JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc 
                    ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
                    AND kcu.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA
                WHERE kcu.CONSTRAINT_NAME = ?
                AND kcu.TABLE_SCHEMA = DATABASE()
                AND kcu.TABLE_NAME = 'motores_asignaciones_activas'
            ", [$fk->CONSTRAINT_NAME]);
            
            if (!empty($details)) {
                $foreignKeyInfo[$fk->CONSTRAINT_NAME] = $details[0];
            }
            
            // Eliminar la clave foránea temporalmente
            try {
                DB::statement("ALTER TABLE motores_asignaciones_activas DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                echo "Clave foránea {$fk->CONSTRAINT_NAME} eliminada temporalmente.\n";
            } catch (\Exception $e) {
                echo "No se pudo eliminar FK {$fk->CONSTRAINT_NAME}: {$e->getMessage()}\n";
            }
        }
        
        // PASO 2: Eliminar el índice único problemático
        $indexes = DB::select("
            SELECT DISTINCT INDEX_NAME 
            FROM INFORMATION_SCHEMA.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'motores_asignaciones_activas' 
            AND INDEX_NAME LIKE '%unique_active%'
        ");
        
        foreach ($indexes as $index) {
            try {
                DB::statement("ALTER TABLE motores_asignaciones_activas DROP INDEX {$index->INDEX_NAME}");
                echo "Índice {$index->INDEX_NAME} eliminado exitosamente.\n";
            } catch (\Exception $e) {
                echo "No se pudo eliminar el índice {$index->INDEX_NAME}: {$e->getMessage()}\n";
            }
        }
        
        // PASO 3: Asegurarnos de que la columna Estado_asignacion tenga el tamaño correcto
        Schema::table('motores_asignaciones_activas', function (Blueprint $table) {
            $table->string('Estado_asignacion', 50)->change();
        });
        
        // PASO 4: Agregar las nuevas columnas si no existen
        Schema::table('motores_asignaciones_activas', function (Blueprint $table) {
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Estado_final_propuesto')) {
                $table->string('Estado_final_propuesto', 50)->nullable();
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Trabajo_realizado')) {
                $table->text('Trabajo_realizado')->nullable();
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Observaciones_entrega')) {
                $table->text('Observaciones_entrega')->nullable();
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Fecha_entrega_tecnico')) {
                $table->timestamp('Fecha_entrega_tecnico')->nullable();
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Fecha_entrada_admin')) {
                $table->timestamp('Fecha_entrada_admin')->nullable();
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Id_usuario_entrada')) {
                $table->unsignedBigInteger('Id_usuario_entrada')->nullable();
            }
        });
        
        // PASO 5: Crear un índice simple (NO ÚNICO) para optimizar consultas
        try {
            $existingIndex = DB::select("
                SELECT INDEX_NAME 
                FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'motores_asignaciones_activas' 
                AND INDEX_NAME = 'idx_motor_estado'
            ");
            
            if (empty($existingIndex)) {
                DB::statement("
                    CREATE INDEX idx_motor_estado 
                    ON motores_asignaciones_activas (Id_motores, Estado_asignacion)
                ");
                echo "Índice idx_motor_estado creado exitosamente.\n";
            }
        } catch (\Exception $e) {
            echo "Advertencia al crear índice: {$e->getMessage()}\n";
        }
        
        // PASO 6: Recrear las claves foráneas
        foreach ($foreignKeyInfo as $constraintName => $info) {
            try {
                $onUpdate = $info->UPDATE_RULE ?? 'NO ACTION';
                $onDelete = $info->DELETE_RULE ?? 'NO ACTION';
                
                DB::statement("
                    ALTER TABLE motores_asignaciones_activas 
                    ADD CONSTRAINT {$constraintName}
                    FOREIGN KEY ({$info->COLUMN_NAME}) 
                    REFERENCES {$info->REFERENCED_TABLE_NAME}({$info->REFERENCED_COLUMN_NAME})
                    ON UPDATE {$onUpdate}
                    ON DELETE {$onDelete}
                ");
                echo "Clave foránea {$constraintName} recreada exitosamente.\n";
            } catch (\Exception $e) {
                echo "Advertencia al recrear FK {$constraintName}: {$e->getMessage()}\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar el índice simple
        try {
            DB::statement('ALTER TABLE motores_asignaciones_activas DROP INDEX idx_motor_estado');
        } catch (\Exception $e) {
            // Ignorar si no existe
        }
        
        Schema::table('motores_asignaciones_activas', function (Blueprint $table) {
            $table->string('Estado_asignacion', 20)->change();
            
            if (Schema::hasColumn('motores_asignaciones_activas', 'Estado_final_propuesto')) {
                $table->dropColumn('Estado_final_propuesto');
            }
            if (Schema::hasColumn('motores_asignaciones_activas', 'Trabajo_realizado')) {
                $table->dropColumn('Trabajo_realizado');
            }
            if (Schema::hasColumn('motores_asignaciones_activas', 'Observaciones_entrega')) {
                $table->dropColumn('Observaciones_entrega');
            }
            if (Schema::hasColumn('motores_asignaciones_activas', 'Fecha_entrega_tecnico')) {
                $table->dropColumn('Fecha_entrega_tecnico');
            }
            if (Schema::hasColumn('motores_asignaciones_activas', 'Fecha_entrada_admin')) {
                $table->dropColumn('Fecha_entrada_admin');
            }
            if (Schema::hasColumn('motores_asignaciones_activas', 'Id_usuario_entrada')) {
                $table->dropColumn('Id_usuario_entrada');
            }
        });
    }
};