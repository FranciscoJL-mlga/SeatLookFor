<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comentario', function (Blueprint $table) {
            $table->id('idCom');
            $table->text('opinion');
            $table->decimal('valoracion', 3, 1);
            $table->mediumText('foto')->nullable();
            $table->unsignedBigInteger('idUsu');
            $table->unsignedBigInteger('idAsi');
            $table->unsignedBigInteger('idEve')->nullable();

            $table->foreign('idUsu')
                ->references('idUsu')
                ->on('usuario')
                ->onDelete('cascade');

            $table->foreign('idAsi')
                ->references('idAsi')
                ->on('asiento')
                ->onDelete('cascade');

            $table->foreign('idEve')
                ->references('idEve')
                ->on('evento')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentario');
    }
};
