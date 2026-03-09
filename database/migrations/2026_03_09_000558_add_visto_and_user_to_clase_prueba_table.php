<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clasePrueba', function (Blueprint $table) {
            $table->boolean('Visto_admin')->default(false)->after('Asistencia');
            $table->unsignedBigInteger('Id_usuario_asistencia')->nullable()->after('Visto_admin');
            $table->foreign('Id_usuario_asistencia')->references('Id_usuarios')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clasePrueba', function (Blueprint $table) {
            $table->dropForeign(['Id_usuario_asistencia']);
            $table->dropColumn(['Visto_admin', 'Id_usuario_asistencia']);
        });
    }
};
