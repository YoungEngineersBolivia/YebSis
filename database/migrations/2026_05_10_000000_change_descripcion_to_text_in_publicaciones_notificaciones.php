<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->text('Descripcion')->nullable()->change();
        });

        Schema::table('notificaciones', function (Blueprint $table) {
            $table->text('Descripcion')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->string('Descripcion')->nullable()->change();
        });

        Schema::table('notificaciones', function (Blueprint $table) {
            $table->string('Descripcion')->nullable()->change();
        });
    }
};
