<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->boolean('es_demo')->default(false)->after('admin');
        });

        Schema::table('establecimiento', function (Blueprint $table) {
            $table->boolean('demo')->default(false)->after('tipo');
        });

        Schema::table('evento', function (Blueprint $table) {
            $table->boolean('demo')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropColumn('es_demo');
        });

        Schema::table('establecimiento', function (Blueprint $table) {
            $table->dropColumn('demo');
        });

        Schema::table('evento', function (Blueprint $table) {
            $table->dropColumn('demo');
        });
    }
};
