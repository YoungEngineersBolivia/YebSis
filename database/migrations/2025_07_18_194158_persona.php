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
            $table->foreignId('Id_Rol')
                  ->constrained('Rol', 'Id_Rol')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Programa', function (Blueprint $table) {
            $table->id('Id_Programa');
            $table->string('Nombre')->nullable();
            $table->string('Descripcion')->nullable();
            $table->string('Foto')->nullable();
            $table->string('Duracion')->nullable();
            $table->string('Rango_edad')->nullable();
            $table->float('Costo')->nullable();
            $table->timestamps();
        });

        Schema::create('Sucursal', function (Blueprint $table) {
            $table->id('Id_Sucursal');
            $table->string('Nombre')->nullable();
            $table->string('Direccion')->nullable();
            $table->timestamps();
        });

        Schema::create('Usuario', function (Blueprint $table) {
            $table->id('Id_Usuario');
            $table->string('Correo')->nullable();
            $table->string('Contrasania')->nullable();
            $table->foreignId('Id_Persona')
                  ->constrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Profesor', function (Blueprint $table) {
            $table->id('Id_Profesor');
            $table->string('Profesion')->nullable();
            $table->foreignId('Id_Persona')
                  ->constrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            $table->foreignId('Id_Usuario')
                  ->constrained('Usuario', 'Id_Usuario')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Estudiante', function (Blueprint $table) {
            $table->id('Cod_estudiante');
            $table->string('Estado')->nullable();
            $table->foreignId('Id_Persona')
                  ->constrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            $table->foreignId('Id_Profesor')
                  ->constrained('Profesor', 'Id_Profesor')
                  ->onDelete('cascade');
            $table->foreignId('Id_Programa')
                  ->constrained('Programa', 'Id_Programa')
                  ->onDelete('cascade');
            $table->foreignId('Id_Sucursal')
                  ->constrained('Sucursal', 'Id_Sucursal')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Pagos', function (Blueprint $table) {
            $table->id('Id_pagos');
            $table->string('Descripcion')->nullable();
            $table->string('Comprobante')->nullable();
            $table->float('Monto_pago')->nullable();
            $table->date('Fecha_pago')->nullable();
            $table->timestamps();
        });

        Schema::create('Tutor', function (Blueprint $table) {
            $table->id('Id_Tutor');
            $table->string('Descuento')->nullable();
            $table->string('Parentezco')->nullable();
            $table->string('Nit')->nullable();
            $table->foreignId('Id_Persona')
                  ->constrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            $table->foreignId('Id_Usuario')
                  ->constrained('Usuario', 'Id_Usuario')
                  ->onDelete('cascade');
            $table->foreignId('Id_Pagos')
                  ->constrained('Pagos', 'Id_pagos')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Clase_de_Prueba', function (Blueprint $table) {
            $table->id('Id_Clase_Prueba');
            $table->date('Fecha_prueba')->nullable();
            $table->time('Hora_prueba')->nullable();
            $table->string('Nombre_estudiante')->nullable();
            $table->foreignId('Id_Persona')
                  ->constrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Publicaciones', function (Blueprint $table) {
            $table->id('Id_Publicaciones');
            $table->string('Imagen')->nullable();
            $table->string('Nombre')->nullable();
            $table->string('Descripcion')->nullable();
            $table->date('Fecha')->nullable();
            $table->time('Hora')->nullable();
            $table->boolean('Estado')->nullable();
            $table->foreignId('Id_Tutor')
                  ->constrained('Tutor', 'Id_Tutor')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Modelo', function (Blueprint $table) {
            $table->id('Id_Modelo');
            $table->string('Nombre_modelo')->nullable();
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_Programa')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Pregunta', function (Blueprint $table) {
            $table->id('Id_pregunta');
            $table->string('Pregunta')->nullable();
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_Programa')
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
            $table->foreignId('Id_profesor')
                  ->constrained('Profesor', 'Id_Profesor')
                  ->onDelete('cascade');
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_Programa')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Horario', function (Blueprint $table) {
            $table->id('Id_horario');
            $table->time('Hora_clase')->nullable();
            $table->string('Dia_clase')->nullable();
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_Programa')
                  ->onDelete('cascade');
            $table->foreignId('Id_profesor')
                  ->constrained('Profesor', 'Id_Profesor')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Citas', function (Blueprint $table) {
            $table->id('Id_citas');
            $table->date('Fecha')->nullable();
            $table->time('Hora')->nullable();
            $table->boolean('Nombre')->nullable();
            $table->foreignId('Id_Tutor')
                  ->constrained('Tutor', 'Id_Tutor')
                  ->onDelete('cascade');
            $table->foreignId('Id_Evaluaciones')
                  ->constrained('Evaluacion', 'Id_evaluacion')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Egresos', function (Blueprint $table) {
            $table->id('Id_egreso');
            $table->string('Tipo')->nullable();
            $table->string('Descripcion_egreso')->nullable();
            $table->date('Fecha_egreso')->nullable();
            $table->float('Monto_egreso')->nullable();
            $table->timestamps();
        });

        Schema::create('Plan_de_pagos', function (Blueprint $table) {
            $table->id('Id_plan_pagos');
            $table->float('Monto_total')->nullable();
            $table->integer('Nro_cuotas')->nullable();
            $table->date('fecha_plan_pagos')->nullable();
            $table->string('Estado_plan')->nullable();
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_Programa')
                  ->onDelete('cascade');
            $table->foreignId('Id_pagos')
                  ->constrained('Pagos', 'Id_pagos')
                  ->onDelete('cascade');
            $table->foreignId('Id_tutor')
                  ->constrained('Tutor', 'Id_Tutor')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Cuotas', function (Blueprint $table) {
            $table->id('Id_cuotas');
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

        Schema::create('Graduados', function (Blueprint $table) {
            $table->id('Id_graduado');
            $table->date('Fecha_graduado')->nullable();
            $table->foreignId('Cod_estudiante')
                  ->constrained('Estudiante', 'Cod_estudiante')
                  ->onDelete('cascade');
            $table->foreignId('Id_programa')
                  ->constrained('Programa', 'Id_Programa')
                  ->onDelete('cascade');
            $table->foreignId('Id_profesor')
                  ->constrained('Profesor', 'Id_Profesor')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Graduados');
        Schema::dropIfExists('Cuotas');
        Schema::dropIfExists('Plan_de_pagos');
        Schema::dropIfExists('Egresos');
        Schema::dropIfExists('Citas');
        Schema::dropIfExists('Horario');
        Schema::dropIfExists('Evaluacion');
        Schema::dropIfExists('Respuesta');
        Schema::dropIfExists('Pregunta');
        Schema::dropIfExists('Modelo');
        Schema::dropIfExists('Publicaciones');
        Schema::dropIfExists('Clase_de_Prueba');
        Schema::dropIfExists('Tutor');
        Schema::dropIfExists('Pagos');
        Schema::dropIfExists('Estudiante');
        Schema::dropIfExists('Profesor');
        Schema::dropIfExists('Usuario');
        Schema::dropIfExists('Sucursal');
        Schema::dropIfExists('Programa');
        Schema::dropIfExists('Persona');
        Schema::dropIfExists('Rol');
    }
};
