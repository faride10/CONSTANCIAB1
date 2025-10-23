<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('CONFERENCIA', function (Blueprint $table) {
            $table->id('ID_CONFERENCIA');
            $table->string('NOMBRE_CONFERENCIA', 250);
            $table->text('TEMA')->nullable();
            $table->dateTime('FECHA_HORA')->nullable();
            $table->string('LUGAR', 250)->nullable();
            $table->integer('NUM_PARTICIPANTES')->nullable();
            $table->foreignId('ID_PONENTE')->nullable()->constrained('PONENTE', 'ID_PONENTE')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('CONFERENCIA');
    }
};