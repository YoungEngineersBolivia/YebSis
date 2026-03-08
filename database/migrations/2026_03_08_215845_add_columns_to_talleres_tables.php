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
        Schema::table('estudiantes_talleres', function (Blueprint $table) {
            $table->string('Estado_inscripcion')->nullable()->after('Id_programas');
            $table->text('Observaciones')->nullable()->after('Estado_inscripcion');
        });

        Schema::table('pagos_talleres', function (Blueprint $table) {
            $table->string('Estado_pago', 100)->nullable()->after('Metodo_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes_talleres', function (Blueprint $table) {
            $table->dropColumn(['Estado_inscripcion', 'Observaciones']);
        });

        Schema::table('pagos_talleres', function (Blueprint $table) {
            $table->dropColumn('Estado_pago');
        });
    }
};
