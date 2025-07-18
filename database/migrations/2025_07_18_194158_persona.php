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
        
    }

    public function down(): void
    {
        // El orden de eliminación debe ser inverso al de creación:
        // Primero la tabla que tiene la clave foránea (Persona), luego la tabla referenciada (Rol).
        Schema::dropIfExists('Persona'); // Corregido: 'Persona' en singular para coincidir con Schema::create
        Schema::dropIfExists('Rol');
    }
};