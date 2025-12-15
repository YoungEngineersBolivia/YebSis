<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renombrar la columna Id_Sucursales a Id_sucursales en la tabla sucursales
        DB::statement('ALTER TABLE `sucursales` CHANGE `Id_Sucursales` `Id_sucursales` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir el cambio
        DB::statement('ALTER TABLE `sucursales` CHANGE `Id_sucursales` `Id_Sucursales` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }
};
