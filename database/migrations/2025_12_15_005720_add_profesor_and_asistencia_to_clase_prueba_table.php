<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('clasePrueba', function (Blueprint $table) {
            $table->unsignedBigInteger('Id_profesores')->nullable()->after('Id_prospectos');
            $table->enum('Asistencia', ['pendiente', 'asistio', 'no_asistio'])->default('pendiente')->after('Id_profesores');
            
            $table->foreign('Id_profesores')
                  ->references('Id_profesores')
                  ->on('profesores')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clasePrueba', function (Blueprint $table) {
            $table->dropForeign(['Id_profesores']);
            $table->dropColumn(['Id_profesores', 'Asistencia']);
        });
    }
};
