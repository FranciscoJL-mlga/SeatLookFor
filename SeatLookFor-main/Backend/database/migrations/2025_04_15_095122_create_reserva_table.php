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
        Schema::create('reserva', function (Blueprint $table) {
            $table->id('idRes');
            $table->date('fechaReserva');
            $table->decimal('totalPrecio',10,2);
/************************************************ */
            $table->unsignedBigInteger('idEve');
            $table->unsignedBigInteger('idAsi');
            $table->unsignedBigInteger('idUsu');
            
/************************************************ */
            $table->foreign('idEve')->references('idEve')->on('evento')->onDelete('cascade');
            
            $table->foreign('idUsu')->references('idUsu')->on('usuario');

            
            $table->foreign('idAsi')->references('idAsi')->on('asiento');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva');
    }
};
