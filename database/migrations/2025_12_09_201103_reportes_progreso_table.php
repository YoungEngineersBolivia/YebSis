<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
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
        Schema::dropIfExists('reportes_progreso');
    }
};
