<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('Id_asistencia');
            $table->integer('Id_estudiantes');
            $table->integer('Id_profesores');
            $table->integer('Id_programas');
            $table->date('Fecha');
            $table->enum('Estado', ['Asistio', 'Falta', 'Licencia', 'Reprogramado']);
            $table->text('Observacion')->nullable();
            $table->date('Fecha_reprogramada')->nullable();
            $table->timestamps();

            // Foreign keys (assuming tables exist and use standard IDs, adjusting if necessary based on YebSis conventions)
            // Note: In YebSis, usually foreign keys are integers without explicit constraint in migration sometimes, 
            // but strict FKs are better. I'll stick to integer definition as per other tables I've seen.
            // If explicit FKs are needed:
            // $table->foreign('Id_estudiantes')->references('Id_estudiantes')->on('estudiantes');
            // $table->foreign('Id_profesores')->references('Id_profesores')->on('profesores');
            // $table->foreign('Id_programas')->references('Id_programas')->on('programas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
