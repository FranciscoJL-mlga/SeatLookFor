<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zona', function (Blueprint $table) {
            $table->id('idZona');
            $table->string('nombre');
            $table->unsignedBigInteger('idEst');

            
            $table->foreign('idEst')
                  ->references('idEst')
                  ->on('establecimiento')
                  ->onDelete('cascade');

            $table->timestamps(); // opcional
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zona');
    }
};
