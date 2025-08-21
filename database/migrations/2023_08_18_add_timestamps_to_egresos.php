<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('egresos')) {
            Schema::table('egresos', function (Blueprint $table) {
                if (!Schema::hasColumn('egresos', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('egresos', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('egresos')) {
            Schema::table('egresos', function (Blueprint $table) {
                if (Schema::hasColumn('egresos', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('egresos', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
};

