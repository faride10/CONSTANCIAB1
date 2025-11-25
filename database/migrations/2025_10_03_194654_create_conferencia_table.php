<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conferencia', function (Blueprint $table) {
            $table->id('id_conferencia');
            $table->string('nombre_conferencia', 250);
            $table->text('tema')->nullable();
            $table->dateTime('fecha_hora')->nullable();
            $table->string('lugar', 250)->nullable();
            $table->integer('num_participantes')->nullable();
            $table->foreignId('id_ponente')->nullable()->constrained('ponente', 'id_ponente')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conferencia');
    }
};