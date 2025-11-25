<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conference_attendances', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('conference_id');
            $table->foreign('conference_id')->references('ID_CONFERENCIA')->on('conferencia')->onDelete('cascade');
            
            $table->string('student_control_number'); 
            
            $table->string('verification_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            
            $table->enum('status', ['pending', 'confirmed'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conference_attendances');
    }
};