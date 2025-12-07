<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('constancia', function (Blueprint $table) {
            $table->id('id_constancia');
            
            $table->string('num_control', 30)->unique();
            
            $table->foreign('num_control')
                  ->references('num_control')
                  ->on('alumnos')
                  ->onDelete('cascade');
                  
            $table->date('fecha_emision')->useCurrent();
          
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('constancia');
    }
};