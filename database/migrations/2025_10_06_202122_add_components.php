<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla para registrar los motores disponibles
        Schema::create('motores', function (Blueprint $table) {
            $table->id('Id_motores');
            $table->string('Id_motor')->unique(); // 001, 002, 003, etc.
            $table->string('Estado')->default('Funcionando'); // Funcionando, Descompuesto, En Proceso
            $table->text('Observacion')->nullable();
            $table->foreignId('Id_sucursales')
                  ->nullable()
                  ->constrained('sucursales', 'Id_Sucursales')
                  ->onDelete('set null');
            $table->timestamps();
        });

        // Tabla para asignar motores a técnicos (profesores) para reparación
        Schema::create('motores_asignados', function (Blueprint $table) {
            $table->id('Id_motores_asignados');
            $table->foreignId('Id_motores')
                  ->constrained('motores', 'Id_motores')
                  ->onDelete('cascade');
            $table->foreignId('Id_profesores')
                  ->constrained('profesores', 'Id_profesores')
                  ->onDelete('cascade');
            $table->string('Estado_asignacion')->default('En Proceso'); // En Proceso, Completado, Cancelado
            $table->date('Fecha_asignacion');
            $table->date('Fecha_entrega')->nullable();
            $table->text('Observacion_inicial')->nullable(); // Observación al asignar
            $table->timestamps();
        });

        // Tabla para registrar reportes de mantenimiento de motores
        Schema::create('reportes_mantenimiento', function (Blueprint $table) {
            $table->id('Id_reportes');
            $table->foreignId('Id_motores_asignados')
                  ->constrained('motores_asignados', 'Id_motores_asignados')
                  ->onDelete('cascade');
            $table->string('Estado_final'); // Descompuesto, En proceso, Funcionando
            $table->text('Observaciones')->nullable();
            $table->date('Fecha_reporte');
            $table->timestamps();
        });

        // Tabla para registrar entradas y salidas de motores (historial)
        Schema::create('motores_movimientos', function (Blueprint $table) {
            $table->id('Id_movimientos');
            $table->foreignId('Id_motores')
                  ->constrained('motores', 'Id_motores')
                  ->onDelete('cascade');
            $table->string('Tipo_movimiento'); // 'Entrada' o 'Salida'
            $table->date('Fecha');
            $table->foreignId('Id_sucursales')
                  ->constrained('sucursales', 'Id_Sucursales')
                  ->onDelete('cascade');
            $table->string('Estado_ubicacion'); // Entrada, Salida, Andres, Saip, Nayeli, etc.
            $table->string('Ultimo_tecnico')->nullable();
            $table->text('Observacion')->nullable();
            $table->foreignId('Id_usuarios')
                  ->nullable()
                  ->constrained('usuarios', 'Id_usuarios')
                  ->onDelete('set null');
            $table->timestamps();
        });

        // Tabla para solicitudes de salida de motores
        Schema::create('solicitudes_salida', function (Blueprint $table) {
            $table->id('Id_solicitudes');
            $table->foreignId('Id_motores')
                  ->constrained('motores', 'Id_motores')
                  ->onDelete('cascade');
            $table->foreignId('Id_usuarios')
                  ->constrained('usuarios', 'Id_usuarios')
                  ->onDelete('cascade');
            $table->date('Fecha_solicitud');
            $table->string('Estado_solicitud')->default('Pendiente'); // Pendiente, Aprobada, Rechazada
            $table->text('Motivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_salida');
        Schema::dropIfExists('motores_movimientos');
        Schema::dropIfExists('reportes_mantenimiento');
        Schema::dropIfExists('motores_asignados');
        Schema::dropIfExists('motores');
    }
};