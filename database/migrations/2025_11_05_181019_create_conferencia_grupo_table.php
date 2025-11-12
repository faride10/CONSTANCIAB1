<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
    Schema::create('conferencia_grupo', function (Blueprint $table) {

        $table->unsignedBigInteger('ID_CONFERENCIA');
        $table->foreign('ID_CONFERENCIA')
              ->references('ID_CONFERENCIA')->on('conferencia')     
              ->onDelete('cascade');    

        $table->unsignedBigInteger('ID_GRUPO');
        $table->foreign('ID_GRUPO')
              ->references('ID_GRUPO')->on('grupo')     
              ->onDelete('cascade');    
        $table->primary(['ID_CONFERENCIA', 'ID_GRUPO']);
    });
  }
  
    public function down(): void
    {
        Schema::dropIfExists('conferencia_grupo');
    }
};
