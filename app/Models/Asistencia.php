<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'ASISTENCIA';    
    protected $primaryKey = 'ID_ASISTENCIA';    

    public $timestamps = true; 

    protected $fillable = [
        'ID_CONFERENCIA',
        'NUM_CONTROL',
        'FECHA_REGISTRO',     
        'VERIFICATION_TOKEN',
        'TOKEN_EXPIRES_AT',
        'STATUS'
    ];

    protected $casts = [
        'TOKEN_EXPIRES_AT' => 'datetime',
        'FECHA_REGISTRO' => 'datetime'
    ];

    public function conferencia()
    {
        return $this->belongsTo(Conferencia::class, 'ID_CONFERENCIA', 'ID_CONFERENCIA');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'NUM_CONTROL', 'NUM_CONTROL');
    }
}