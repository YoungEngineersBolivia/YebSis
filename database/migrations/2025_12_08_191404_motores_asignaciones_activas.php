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
        // PASO 0: Verificar si la tabla existe, si no, crearla
        if (!Schema::hasTable('motores_asignaciones_activas')) {
            Schema::create('motores_asignaciones_activas', function (Blueprint $table) {
                $table->id('Id_asignacion');
                $table->unsignedBigInteger('Id_motores');
                $table->unsignedBigInteger('Id_profesores');
                $table->unsignedBigInteger('Id_movimiento_salida')->nullable();
                $table->timestamp('Fecha_salida')->nullable();
                $table->string('Estado_motor_salida', 50)->nullable();
                $table->text('Motivo_salida')->nullable();
                $table->string('Estado_asignacion', 50)->default('Activa');
                $table->string('Estado_final_propuesto', 50)->nullable();
                $table->text('Trabajo_realizado')->nullable();
                $table->text('Observaciones_entrega')->nullable();
                $table->timestamp('Fecha_entrega_tecnico')->nullable();
                $table->timestamp('Fecha_entrada_admin')->nullable();
                $table->unsignedBigInteger('Id_usuario_entrada')->nullable();
                $table->timestamps();
                
                // Índices
                $table->index(['Id_motores', 'Estado_asignacion'], 'idx_motor_estado');
                $table->index('Id_profesores');
                $table->index('Estado_asignacion');
                
                // Foreign keys
                $table->foreign('Id_motores', 'motores_asignaciones_activas_id_motores_foreign')
                    ->references('Id_motores')
                    ->on('motores')
                    ->onDelete('cascade');
                    
                $table->foreign('Id_profesores', 'motores_asignaciones_activas_id_profesores_foreign')
                    ->references('Id_profesores')
                    ->on('profesores')
                    ->onDelete('cascade');
            });
            
            echo "✓ Tabla motores_asignaciones_activas creada exitosamente.\n";
            return; // Salir aquí, no necesitamos hacer nada más
        }
        
        echo "→ Tabla motores_asignaciones_activas ya existe, aplicando modificaciones...\n";
        
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
                echo "✓ Clave foránea {$fk->CONSTRAINT_NAME} eliminada temporalmente.\n";
            } catch (\Exception $e) {
                echo "✗ No se pudo eliminar FK {$fk->CONSTRAINT_NAME}: {$e->getMessage()}\n";
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
                echo "✓ Índice {$index->INDEX_NAME} eliminado exitosamente.\n";
            } catch (\Exception $e) {
                echo "✗ No se pudo eliminar el índice {$index->INDEX_NAME}: {$e->getMessage()}\n";
            }
        }
        
        // PASO 3: Asegurarnos de que la columna Estado_asignacion tenga el tamaño correcto
        Schema::table('motores_asignaciones_activas', function (Blueprint $table) {
            $table->string('Estado_asignacion', 50)->change();
        });
        echo "✓ Columna Estado_asignacion modificada.\n";
        
        // PASO 4: Agregar las nuevas columnas si no existen
        Schema::table('motores_asignaciones_activas', function (Blueprint $table) {
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Estado_final_propuesto')) {
                $table->string('Estado_final_propuesto', 50)->nullable();
                echo "✓ Columna Estado_final_propuesto agregada.\n";
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Trabajo_realizado')) {
                $table->text('Trabajo_realizado')->nullable();
                echo "✓ Columna Trabajo_realizado agregada.\n";
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Observaciones_entrega')) {
                $table->text('Observaciones_entrega')->nullable();
                echo "✓ Columna Observaciones_entrega agregada.\n";
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Fecha_entrega_tecnico')) {
                $table->timestamp('Fecha_entrega_tecnico')->nullable();
                echo "✓ Columna Fecha_entrega_tecnico agregada.\n";
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Fecha_entrada_admin')) {
                $table->timestamp('Fecha_entrada_admin')->nullable();
                echo "✓ Columna Fecha_entrada_admin agregada.\n";
            }
            if (!Schema::hasColumn('motores_asignaciones_activas', 'Id_usuario_entrada')) {
                $table->unsignedBigInteger('Id_usuario_entrada')->nullable();
                echo "✓ Columna Id_usuario_entrada agregada.\n";
            }
        });
        
        // PASO 5: Verificar y corregir timestamps si tienen nombres no estándar
        $timestampColumns = DB::select("
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'motores_asignaciones_activas'
            AND (COLUMN_NAME LIKE '%created_at%' OR COLUMN_NAME LIKE '%updated_at%')
        ");
        
        $hasCreatedAt = false;
        $hasUpdatedAt = false;
        $hasOldCreatedAt = false;
        $hasOldUpdatedAt = false;
        
        foreach ($timestampColumns as $col) {
            if ($col->COLUMN_NAME === 'created_at') $hasCreatedAt = true;
            if ($col->COLUMN_NAME === 'updated_at') $hasUpdatedAt = true;
            if ($col->COLUMN_NAME === 'motores_asignaciones_activascreated_at') $hasOldCreatedAt = true;
            if ($col->COLUMN_NAME === 'motores_asignaciones_activasupdated_at') $hasOldUpdatedAt = true;
        }
        
        // Renombrar timestamps antiguos si existen
        if ($hasOldCreatedAt && !$hasCreatedAt) {
            DB::statement("
                ALTER TABLE motores_asignaciones_activas 
                CHANGE COLUMN motores_asignaciones_activascreated_at created_at TIMESTAMP NULL
            ");
            echo "✓ Columna created_at renombrada.\n";
        } elseif (!$hasCreatedAt) {
            Schema::table('motores_asignaciones_activas', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable();
            });
            echo "✓ Columna created_at creada.\n";
        }
        
        if ($hasOldUpdatedAt && !$hasUpdatedAt) {
            DB::statement("
                ALTER TABLE motores_asignaciones_activas 
                CHANGE COLUMN motores_asignaciones_activasupdated_at updated_at TIMESTAMP NULL
            ");
            echo "✓ Columna updated_at renombrada.\n";
        } elseif (!$hasUpdatedAt) {
            Schema::table('motores_asignaciones_activas', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable();
            });
            echo "✓ Columna updated_at creada.\n";
        }
        
        // PASO 6: Crear un índice simple (NO ÚNICO) para optimizar consultas
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
                echo "✓ Índice idx_motor_estado creado exitosamente.\n";
            } else {
                echo "→ Índice idx_motor_estado ya existe.\n";
            }
        } catch (\Exception $e) {
            echo "⚠ Advertencia al crear índice: {$e->getMessage()}\n";
        }
        
        // PASO 7: Recrear las claves foráneas
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
                echo "✓ Clave foránea {$constraintName} recreada exitosamente.\n";
            } catch (\Exception $e) {
                echo "⚠ Advertencia al recrear FK {$constraintName}: {$e->getMessage()}\n";
            }
        }
        
        echo "\n✓ Migración completada exitosamente.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motores_asignaciones_activas');
    }
};