<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void {
    Schema::create('ponente', function (Blueprint $table) {
        $table->id('id_ponente');
        $table->string('nombre', 200);
        $table->string('titulo', 150)->nullable();
        $table->string('cargo', 150)->nullable();
        $table->string('empresa', 200)->nullable();
        $table->string('correo', 200)->nullable();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('ponente');
    }
};
