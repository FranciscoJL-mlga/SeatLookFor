<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento', function (Blueprint $table) {
            $table->string('codigo', 12)->nullable()->unique()->after('idEst');
        });

        // Generate unique codes for existing rows
        $ids = DB::table('evento')->pluck('idEve');
        foreach ($ids as $id) {
            do {
                $code = Str::random(8);
            } while (DB::table('evento')->where('codigo', $code)->exists());
            DB::table('evento')->where('idEve', $id)->update(['codigo' => $code]);
        }
    }

    public function down(): void
    {
        Schema::table('evento', function (Blueprint $table) {
            $table->dropColumn('codigo');
        });
    }
};
