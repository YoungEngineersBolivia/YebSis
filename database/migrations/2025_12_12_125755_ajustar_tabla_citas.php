<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migración para ajustar la tabla de citas
     * Ejecutar: php artisan make:migration ajustar_tabla_citas
     */
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            // Hacer Id_evaluaciones nullable ya que no siempre habrá una evaluación al agendar
            $table->unsignedBigInteger('Id_evaluaciones')->nullable()->change();
            
            // Agregar campo para el motivo de la cita (opcional)
            $table->text('motivo')->nullable()->after('Hora');
            
            // Agregar campo para el estado de la cita
            $table->string('estado', 50)->default('pendiente')->after('motivo');
            // Estados posibles: pendiente, confirmada, completada, cancelada
            
            // Agregar relación con estudiantes si quieres vincular directamente
            $table->foreignId('Id_estudiantes')
                  ->nullable()
                  ->after('Id_tutores')
                  ->constrained('estudiantes', 'Id_estudiantes')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['motivo', 'estado']);
            $table->dropForeign(['Id_estudiantes']);
            $table->dropColumn('Id_estudiantes');
        });
    }
};