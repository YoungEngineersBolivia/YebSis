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
        Schema::create('Clase_de_Prueba', function(Blueprint $table){
            $table->id('Id_Clase_Prueba');
            $table->date('Fecha_prueba')->nullable();
            $table->time('Hora_prueba')->nullable();
            $table->string('Nombre_estudiante')->nullable();
            $table->foreignId('Id_Persona')
                  ->contrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            $table->timestamps();

        });
        Schema::create('Usuario', function(Blueprint $table){
            $table->id('Id_Usuario');
            $table->string('Correo')->nullable();
            $table->string('Contrasania')->nullable();
            $table->foreignId('Id_Persona')
                  ->contrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            $table->timestamps();

           

        });
         Schema::create('Programa', function(Blueprint $table){
            $table->id('Id_Programa');
            $table->string('Nombre')->nullable();
            $table->string('Descripcion')->nullable();
            $table->string('Foto')->nullable();
            $table->string('Duracion')->nullable();
            $table->string('Rango_edad')->nullable();
            $table->float('Costo')->nullable();
        });
        Schema::create('Sucursal', function(Blueprint $table){
            $table->id('Id_Sucursal');
            $table->string('Nombre')->nullable();
            $table->string('Direccion')->nullable();
        });
        Schema::create('Tutor', function(Blueprint $table){
            $table->id('Id_Tutor');
            $table->string('Descuento')->nullable();
            $table->string('Parentezco')->nullable();
            $table->string('Nit')->nullable();
            $table->foreignId('Id_Persona')
                  ->contrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            $table->foreignId('Id_Usuario')
                  ->contrained('Usuario', 'Id_Usuario')
                  ->onDelete('cascade');
           // $table->foreignId('Id_Pagos')->contrained('Pagos', 'Id_Pagos')->onDelete('cascade');
           $table->timestamps();
        });
      
         Schema::create('Profesor', function(Blueprint $table){
            $table->id('Id_Profesor');
            $table->string('Profesion')->nullable();
            $table->foreignId('Id_Persona')
                  ->contrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            $table->foreignId('Id_Usuario')
                  ->contrained('Usuario', 'Id_Usuario')
                  ->onDelete('cascade');
             $table->foreignId('Cod_estudiante')
                  ->contrained('Usuario', 'Id_Usuario')
                  ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('Estudiante', function(Blueprint $table){
            $table->id('Cod_estudiante');
            $table->string('Estado')->nullable();
            $table->foreignId('Id_Persona')
                  ->contrained('Persona', 'Id_Persona')
                  ->onDelete('cascade');
            
            $table->foreignId('Id_Profesor')
                  ->contrained('Profesor', 'Id_Profesor')
                  ->onDelete('cascade');
             $table->foreignId('Id_Programa')
                  ->contrained('Programa', 'Id_Programa')
                  ->onDelete('cascade');
            $table->foreignId('Id_Sucursal')
                  ->contrained('Sucursal', 'Id_Sucursal')
                  ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('Publicaciones', function(Blueprint $table){
            $table->id('Id_Publicaciones');
            $table->string('Imagen')->nullable();
            $table->string('Nombre')->nullable();
            $table->string('Descripcion')->nullable();
            $table->date('Fecha')->nullable();
            $table->time('Hora')->nullable();
            $table->boolean('Estado')->nullable();
            $table->foreignId('Id_Tutor')
                  ->contrained('Tutor', 'Id_Tutor')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('Citas', function(Blueprint $table){
            $table->id('Id_citas');
            $table->date('Fecha')->nullable();
            $table->time('Hora')->nullable();
            $table->boolean('Nombre')->nullable();
            $table->foreignId('Id_Tutor')
                  ->contrained('Tutor', 'Id_Tutor')
                  ->onDelete('cascade');
           // $table->foreignId('Id_Evaluaciones')->contrained('Tutor', 'Id_Evaluaciones')->onDelete('cascade');
            $table->timestamps();
        });

    }

    public function down(): void
    {
       
        Schema::dropIfExists('Persona'); 
        Schema::dropIfExists('Rol');
        Schema::dropIfExists('Usuario');
        Schema::dropIfExists('Clase_de_Prueba');
        Schema::dropIfExists('Programa');
        Schema::dropIfExists('Sucursal');
        Schema::dropIfExists('Tutor');
        Schema::dropIfExists('Profesor');
        Schema::dropIfExists('Estudiante');
        Schema::dropIfExists('Publicaciones');
        Schema::dropIfExists('Citas');


        
    }
};