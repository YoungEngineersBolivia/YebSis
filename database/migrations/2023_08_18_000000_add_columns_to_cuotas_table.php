<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cuotas') && !Schema::hasColumn('cuotas', 'pagado')) {
            Schema::table('cuotas', function (Blueprint $table) {
                $table->boolean('pagado')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cuotas') && Schema::hasColumn('cuotas', 'pagado')) {
            Schema::table('cuotas', function (Blueprint $table) {
                $table->dropColumn('pagado');
            });
        }
    }
};
