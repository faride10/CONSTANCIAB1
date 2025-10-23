<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void {
    Schema::create('PONENTE', function (Blueprint $table) {
        $table->id('ID_PONENTE');
        $table->string('NOMBRE', 200);
        $table->string('TITULO', 150)->nullable();
        $table->string('CARGO', 150)->nullable();
        $table->string('EMPRESA', 200)->nullable();
        $table->string('CORREO', 200)->nullable();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('ponente');
    }
};
