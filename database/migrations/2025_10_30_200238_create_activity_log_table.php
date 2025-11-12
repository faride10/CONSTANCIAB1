<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
{
    Schema::create('activity_log', function (Blueprint $table) {
        $table->id();   
        $table->string('tipo_accion', 50); 
        $table->string('descripcion', 255); 
        $table->timestamps(); 
    });
}

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
