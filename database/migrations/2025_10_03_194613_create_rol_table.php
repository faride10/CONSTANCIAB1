<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
public function up(): void {
    Schema::create('ROL', function (Blueprint $table) {
        $table->id('ID_ROL');
        $table->string('NOMBRE_ROL', 50)->unique();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('rol');
    }
};
