<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asientos_evento', function (Blueprint $table) {
            $table->id('idAE');
            $table->decimal('precio');
            $table->unsignedBigInteger('idAsi');
            $table->unsignedBigInteger('idEve');
            $table->foreign('idAsi')->references('idAsi')->on('asiento');
            $table->foreign('idEve')->references('idEve')->on('evento');
        });
    }

    public function down(): void {
        Schema::dropIfExists('asientos_evento');
    }
};
