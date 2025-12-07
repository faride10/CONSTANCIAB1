<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('conferencia_grupo', function (Blueprint $table) {

            $table->unsignedBigInteger('id_conferencia');
            $table->foreign('id_conferencia')
                  ->references('id_conferencia')
                  ->on('conferencia')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('id_grupo');
            $table->foreign('id_grupo')
                  ->references('id_grupo')
                  ->on('grupo')
                  ->onDelete('cascade');
                  
            $table->primary(['id_conferencia', 'id_grupo']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('conferencia_grupo');
    }
};