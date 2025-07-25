<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('Id_roles');
            $table->string('Nombre_rol')->nullable();
            $table->timestamps();
        });

        Schema::create('personas', function (Blueprint $table) {
            $table->id('Id_personas');
            $table->string('Nombre')->nullable();
            $table->string('Apellido')->unique()->nullable();
            $table->string('Genero')->nullable();
            $table->string('Direccion_domicilio')->nullable();
            $table->date('Fecha_nacimiento')->nullable();
            $table->date('Fecha_registro')->nullable();
            $table->string('Celular')->nullable();
            $table->foreignId('Id_roles')
                  ->constrained('roles', 'Id_roles')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('programas', function (Blueprint $table) {
            $table->id('Id_programas');
            $table->string('Nombre')->nullable();
            $table->string('Descripcion')->nullable();
            $table->string('Foto')->nullable();
            $table->string('Duracion')->nullable();
            $table->string('Rango_edad')->nullable();
            $table->float('Costo')->nullable();
            $table->timestamps();
        });

        Schema::create('sucursales', function (Blueprint $table) {
            $table->id('Id_Sucursales');
            $table->string('Nombre')->nullable();
            $table->string('Direccion')->nullable();
            $table->timestamps();
        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('Id_Usuarios');
            $table->string('Correo')->nullable();
            $table->string('Contrasania')->nullable();
            $table->foreignId('Id_personas')
                  ->constrained('personas', 'Id_personas')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('profesores', function (Blueprint $table) {
            $table->id('Id_profesores');
            $table->string('Profesion')->nullable();
            $table->foreignId('Id_personas')
                  ->constrained('personas', 'Id_personas')
                  ->onDelete('cascade');
            $table->foreignId('Id_usuarios')
                  ->constrained('usuarios', 'Id_usuarios')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id('Id_estudiantes');
            $table->string('Cod_estudiante') ;
            $table->string('Estado')->nullable();
            $table->foreignId('Id_Personas')
                  ->constrained('personas', 'Id_personas')
                  ->onDelete('cascade');
            $table->foreignId('Id_profesores')
                  ->constrained('profesores', 'Id_profesores')
                  ->onDelete('cascade');
            $table->foreignId('Id_programas')
                  ->constrained('programas', 'Id_programas')
                  ->onDelete('cascade');
            $table->foreignId('Id_sucursales')
                  ->constrained('sucursales', 'Id_sucursales')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id('Id_pagos');
            $table->string('Descripcion')->nullable();
            $table->string('Comprobante')->nullable();
            $table->float('Monto_pago')->nullable();
            $table->date('Fecha_pago')->nullable();
            $table->timestamps();
        });

        Schema::create('tutores', function (Blueprint $table) {
            $table->id('Id_tutores');
            $table->string('Descuento')->nullable();
            $table->string('Parentezco')->nullable();
            $table->string('Nit')->nullable();
            $table->foreignId('Id_personas')
                  ->constrained('personas', 'Id_personas')
                  ->onDelete('cascade');
            $table->foreignId('Id_usuarios')
                  ->constrained('usuarios', 'Id_usuarios')
                  ->onDelete('cascade');
            $table->foreignId('Id_pagos')
                  ->constrained('pagos', 'Id_pagos')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('clases_de_Prueba', function (Blueprint $table) {
            $table->id('Id_clases_Prueba');
            $table->date('Fecha_prueba')->nullable();
            $table->time('Hora_prueba')->nullable();
            $table->string('Nombre_estudiante')->nullable();
            $table->foreignId('Id_personas')
                  ->constrained('personas', 'Id_personas')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id('Id_publicaciones');
            $table->string('Imagen')->nullable();
            $table->string('Nombre')->nullable();
            $table->string('Descripcion')->nullable();
            $table->date('Fecha')->nullable();
            $table->time('Hora')->nullable();
            $table->boolean('Estado')->nullable();
            $table->foreignId('Id_tutores')
                  ->constrained('tutores', 'Id_tutores')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('modelos', function (Blueprint $table) {
            $table->id('Id_modelos');

            $table->string('Nombre_modelo')->nullable();
            $table->foreignId('Id_programa')
                  ->constrained('programas', 'Id_programas')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('preguntas', function (Blueprint $table) {
            $table->id('Id_preguntas');
            $table->string('Pregunta')->nullable();
            $table->foreignId('Id_programas')
                  ->constrained('programas', 'Id_programas')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('respuestas', function (Blueprint $table) {
            $table->id('Id_respuestas');
            $table->string('Respuesta')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id('Id_evaluaciones');
            $table->date('fecha_evaluacion')->nullable();
            $table->foreignId('Id_estudiantes')
                  ->constrained('estudiantes', 'Id_estudiantes')
                  ->onDelete('cascade');
            $table->foreignId('Id_preguntas')
                  ->constrained('Preguntas', 'Id_preguntas')
                  ->onDelete('cascade');
            $table->foreignId('Id_respuestas')
                  ->constrained('Respuestas', 'Id_respuestas')
                  ->onDelete('cascade');
            $table->foreignId('Id_modelos')->constrained('modelos', 'Id_modelos')->onDelete('cascade');
            $table->foreignId('Id_profesores')
                  ->constrained('profesores', 'Id_profesores')
                  ->onDelete('cascade');
            $table->foreignId('Id_programas')
                  ->constrained('programas', 'Id_programas')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('horarios', function (Blueprint $table) {
            $table->id('Id_horarios');
            $table->time('Hora_clase')->nullable();
            $table->string('Dia_clase')->nullable();
            $table->foreignId('Id_programas')
                  ->constrained('programas', 'Id_programas')
                  ->onDelete('cascade');
            $table->foreignId('Id_profesores')
                  ->constrained('profesores', 'Id_profesores')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('citas', function (Blueprint $table) {
            $table->id('Id_citas');
            $table->date('Fecha')->nullable();
            $table->time('Hora')->nullable();
            $table->boolean('Nombre')->nullable();
            $table->foreignId('Id_tutores')
                  ->constrained('tutores', 'Id_tutores')
                  ->onDelete('cascade');
            $table->foreignId('Id_evaluaciones')
                  ->constrained('evaluaciones', 'Id_evaluaciones')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('egresos', function (Blueprint $table) {
            $table->id('Id_egreso');
            $table->string('Tipo')->nullable();
            $table->string('Descripcion_egreso')->nullable();
            $table->date('Fecha_egreso')->nullable();
            $table->float('Monto_egreso')->nullable();
            $table->timestamps();
        });

        Schema::create('planes_pagos', function (Blueprint $table) {
            $table->id('Id_planes_pagos');
            $table->float('Monto_total')->nullable();
            $table->integer('Nro_cuotas')->nullable();
            $table->date('fecha_plan_pagos')->nullable();
            $table->string('Estado_plan')->nullable();
            $table->foreignId('Id_programas')
                  ->constrained('programas', 'Id_programas')
                  ->onDelete('cascade');
            $table->foreignId('Id_pagos')
                  ->constrained('pagos', 'Id_pagos')
                  ->onDelete('cascade');
            $table->foreignId('Id_tutores')
                  ->constrained('tutores', 'Id_tutores')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cuotas', function (Blueprint $table) {
            $table->id('Id_cuotas');
            $table->integer('Nro_de_cuota')->nullable();
            $table->date('Fecha_vencimiento')->nullable();
            $table->float('Monto_cuota')->nullable();
            $table->float('Monto_pagado')->nullable();
            $table->string('Estado_cuota')->nullable();
            $table->foreignId('Id_planes_pagos')
                  ->constrained('planes_pagos', 'Id_planes_pagos')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('graduados', function (Blueprint $table) {
            $table->id('Id_graduado');
            $table->date('Fecha_graduado')->nullable();
            $table->foreignId('Id_estudiantes')
                  ->constrained('estudiantes', 'Id_estudiantes')
                  ->onDelete('cascade');
            $table->foreignId('Id_programas')
                  ->constrained('programas', 'Id_programas')
                  ->onDelete('cascade');
            $table->foreignId('Id_profesores')
                  ->constrained('profesores', 'Id_profesores')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('ganancias', function(Blueprint $table){
            $table->id('Id_ganancias'); 
            $table->float('Total_ganancia')->nullable();
            $table->foreignId('Id_egresos')
                  ->constrained('egresos', 'Id_egreso')
                  ->onDelete('cascade');
            $table->timestamps();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('ganancias'); 
        Schema::dropIfExists('cuotas');
        Schema::dropIfExists('plan_de_pagos');
        Schema::dropIfExists('egresos');
        Schema::dropIfExists('citas');
        Schema::dropIfExists('horarios');
        Schema::dropIfExists('evaluaciones');
        Schema::dropIfExists('respuestas');
        Schema::dropIfExists('preguntas');
        Schema::dropIfExists('modelos');
        Schema::dropIfExists('publicaciones');
        Schema::dropIfExists('clases_de_Prueba');
        Schema::dropIfExists('tutores');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('estudiantes');
        Schema::dropIfExists('profesores');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('sucursales');
        Schema::dropIfExists('programas');
        Schema::dropIfExists('personas');
        Schema::dropIfExists('roles');
    }
};
