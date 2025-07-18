<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Rol', function (Blueprint $table) {
            $table->id('Id_Rol'); 
            $table->string('Nombre_rol')->nullable();
            $table->timestamps(); 
        });

        Schema::create('Persona', function (Blueprint $table) {
            $table->id('Id_Persona'); 
            $table->string('Nombre')->nullable();
            $table->string('Apellido')->unique()->nullable();
            $table->string('Genero')->nullable();
            $table->string('Direccion_domicilio')->nullable();
            $table->date('Fecha_nacimiento')->nullable();
            $table->date('Fecha_registro')->nullable();
            $table->string('Celular')->nullable();
            
            //Relaciones 
            $table->foreignId('Id_Rol')
                  ->constrained('Rol', 'Id_Rol') // Asegura que se referencia a 'Id_Rol' en la tabla 'Rol'
                  ->onDelete('cascade');
            $table->timestamps(); // Buena práctica para created_at y updated_at
        });
        
        Schema::create('Modelo', function (Blueprint $table) {
            $table->id('Id_Rol'); 
            $table->string('Nombre_modelo')->nullable();
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_programa') 
                  ->onDelete('cascade');
            $table->timestamps(); 
        });

        Schema::create('Pregunta', function (Blueprint $table) {
            $table->id('Id_pregunta'); 
            $table->string('Pregunta')->nullable();
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_programa') 
                  ->onDelete('cascade');
            $table->timestamps(); 
        });

        Schema::create('Respuesta', function (Blueprint $table) {
            $table->id('Id_respuesta'); 
            $table->string('Respuesta')->nullable();
            $table->timestamps(); 
        });

        Schema::create('Evaluacion', function (Blueprint $table) {
            $table->id('Id_evaluacion'); 
            $table->date('fecha_evaluacion')->nullable();
            $table->foreignId('Cod_estudiante')
                  ->constrained('Estudiante', 'Cod_estudiante') 
                  ->onDelete('cascade');
            $table->foreignId('Id_pregunta')
                  ->constrained('Pregunta', 'Id_pregunta') 
                  ->onDelete('cascade');
            $table->foreignId('Id_respuesta')
                  ->constrained('Respuesta', 'Id_respuesta') 
                  ->onDelete('cascade');
            $table->foreignId('Id_modelo')
                  ->constrained('Modelo', 'Id_modelo') 
                  ->onDelete('cascade');
            $table->foreignId('Id_profesor')
                  ->constrained('Profesor', 'Id_profesor') 
                  ->onDelete('cascade');
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_programa') 
                  ->onDelete('cascade');
            $table->timestamps(); 
        });

        Schema::create('Horario', function (Blueprint $table) {
            $table->id('Id_horario'); 
            $table->time('Hora_clase')->nullable();
            $table->string('Dia_clase')->nullable();
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_programa') 
                  ->onDelete('cascade');
            $table->foreignId('Id_profesor')
                  ->constrained('Profesor', 'Id_profesor')
                  ->onDelete('cascade');
            $table->timestamps(); 
        });

        Schema::create('Pagos', function(Blueprint $table){
            $table->id('Id_pagos');
            $table->string('Descripcion')->nullable();
            $table->string('Comprobante')->nullable();
            $table->float('Monto_pago')->nullable();
            $table->date('Fecha_pago')->nullable();
            $table->foreignId('Id_tutor')
                  ->constrained('Tutor', 'Id_tutor')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Egresos', function(Blueprint $table){
            $table->id('Id_egreso')->nullable();
            $table->string('Tipo')->nullable();
            $table->string('Descripcion_egreso')->nullable();
            $table->date('Fecha_egreso')->nullable();
            $table->float('Monto_egreso')->nullable();
            $table->timestamps();
        });

        Schema::create('Plan_de_pagos', function(Blueprint $table){
            $table->id('Id_plan_pagos')->nullable();
            $table->float('Monto_total')->nullable();
            $table->integer('Nro_cuotas')->nullable();
            $table->date('fecha_plan_pagos')->nullable();
            $table->string('Estado_plan')->nullable();
            $table->foreingId('Id_programa')
                  ->constrained('Programa', 'Id_programa')
                  ->onDelete('cascade');
            $table->foreingId('Id_pagos')
                  ->constrained('Pagos', 'Id_pagos')
                  ->onDelete('cascade');
            $table->foreignId('Id_tutor')
                  ->constrained('Tutor', 'Id_tutor')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Cuotas', function(Blueprint $table){
            $table->id('Id_cuotas')->nullable();
            $table->integer('Nro_de_cuota')->nullable();
            $table->date('Fecha_vencimiento')->nullable();
            $table->float('Monto_cuota')->nullable();
            $table->float('Monto_pagado')->nullable();
            $table->string('Estado_cuota')->nullable();
            $table->foreignId('Id_plan_pagos')
                  ->constrained('Plan_de_pagos', 'Id_plan_pagos')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Graduados', function(Blueprint $table){
            $table->id('Id_graduado')->nullable();
            $table->date('Fecha_graduado')->nullable();
            $table->foreingId('Cod_estudiante')
                  ->constrained('Estudiantes','Cod_estudiante')
                  ->onDelete('cascade');
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_programa')
                  ->onDelete('cascade');
            $table->foreignId('Id_profesor')
                  ->constrained('Profesor', 'Id_profesor')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Persona'); 
        Schema::dropIfExists('Rol');
        Schema::dropIfExists('Modelo');
        Schema::dropIfExists('Pregunta');
        Schema::dropIFExists('Respuesta');
        Schema::dropIFExists('Evaluacion');
        Schema::dropIFExists('Horario');
        Schema::dropIFExists('Pagos');
        Schema::dropIFExists('Egresos');
        Schema::dropIFExists('Plan_de_pagos');
        Schema::dropIFExists('Cuotas');
        Schema::dropIFExists('Graduados');
    }
};