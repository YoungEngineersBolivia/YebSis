<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla principal de motores (inventario)
        Schema::create('motores', function (Blueprint $table) {
            $table->id('Id_motores');
            $table->string('Id_motor')->unique(); // 001, 002, 003
            $table->enum('Estado', ['Disponible', 'En Reparacion', 'Funcionando', 'Descompuesto'])->default('Disponible');
            $table->enum('Ubicacion_actual', ['Inventario', 'Con Tecnico'])->default('Inventario');
            $table->text('Observacion')->nullable();
            $table->foreignId('Id_sucursales')
                  ->nullable()
                  ->constrained('sucursales', 'Id_Sucursales')
                  ->onDelete('set null');
            $table->foreignId('Id_tecnico_actual') // Técnico que lo tiene actualmente
                  ->nullable()
                  ->constrained('profesores', 'Id_profesores')
                  ->onDelete('set null');
            $table->timestamps();
        });

        // Tabla de movimientos (registra TODO: salidas E entradas)
        Schema::create('motores_movimientos', function (Blueprint $table) {
            $table->id('Id_movimientos');
            $table->foreignId('Id_motores')
                  ->constrained('motores', 'Id_motores')
                  ->onDelete('cascade');
            
            // TIPO DE MOVIMIENTO
            $table->enum('Tipo_movimiento', ['Salida', 'Entrada']);
            
            // DETALLES DEL MOVIMIENTO
            $table->dateTime('Fecha_movimiento');
            $table->foreignId('Id_sucursales')
                  ->constrained('sucursales', 'Id_Sucursales')
                  ->onDelete('cascade');
            
            // TÉCNICO INVOLUCRADO
            $table->foreignId('Id_profesores')
                  ->nullable()
                  ->constrained('profesores', 'Id_profesores')
                  ->onDelete('set null');
            $table->string('Nombre_tecnico'); // Guardado para historial
            
            // ESTADOS
            $table->string('Estado_salida')->nullable(); // Estado al salir del inventario
            $table->string('Estado_entrada')->nullable(); // Estado al regresar al inventario
            
            // OBSERVACIONES
            $table->text('Motivo_salida')->nullable(); // Por qué sale
            $table->text('Trabajo_realizado')->nullable(); // Qué se hizo (solo en entrada)
            $table->text('Observaciones')->nullable();
            
            // USUARIO QUE REGISTRA
            $table->foreignId('Id_usuarios')
                  ->nullable()
                  ->constrained('usuarios', 'Id_usuarios')
                  ->onDelete('set null');
            
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('Tipo_movimiento');
            $table->index('Fecha_movimiento');
            $table->index('Id_profesores');
        });

        // Tabla de reportes de progreso (opcional, mientras está con el técnico)
        Schema::create('reportes_progreso', function (Blueprint $table) {
            $table->id('Id_reporte');
            $table->foreignId('Id_asignacion')
                  ->constrained('motores_asignaciones_activas', 'Id_asignacion')
                  ->onDelete('cascade');
            $table->dateTime('Fecha_reporte');
            $table->enum('Estado_actual', ['En Diagnostico', 'En Reparacion', 'Reparado', 'Irreparable']);
            $table->text('Descripcion_trabajo');
            $table->text('Observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

      Schema::dropIfExists('reportes_progreso');
      Schema::dropIfExists('motores_movimientos');
      Schema::dropIfExists('motores');

      Schema::enableForeignKeyConstraints();
    }
};