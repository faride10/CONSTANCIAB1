<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencia';    
    protected $primaryKey = 'id_asistencia';    

    public $timestamps = true; 

    protected $fillable = [
        'id_conferencia',
        'num_control',
        'fecha_registro',     
        'verification_token',
        'token_expires_at',
        'status'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'fecha_registro' => 'datetime'
    ];

    public function conferencia()
    {
        return $this->belongsTo(Conferencia::class, 'id_conferencia', 'id_conferencia');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'num_control', 'num_control');
    }
}