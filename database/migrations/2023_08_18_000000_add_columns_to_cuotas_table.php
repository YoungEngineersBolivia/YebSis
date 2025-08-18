<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cuotas', function (Blueprint $table) {
            $table->boolean('pagado')->default(false);
            $table->text('Descripcion')->nullable();
            $table->string('Comprobante')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('cuotas', function (Blueprint $table) {
            $table->dropColumn(['pagado', 'Descripcion', 'Comprobante']);
            $table->dropTimestamps();
        });
    }
};
