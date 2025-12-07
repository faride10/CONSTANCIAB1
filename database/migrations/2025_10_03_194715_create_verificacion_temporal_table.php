<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('verificacion_temporal', function (Blueprint $table) {
            $table->string('num_control_fk', 30)->primary();
            $table->foreign('num_control_fk')
                  ->references('num_control')
                  ->on('alumnos')
                  ->onDelete('cascade');
            $table->string('codigo_otp', 10);
            $table->timestamp('expira_en');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verificacion_temporal');
    }
};