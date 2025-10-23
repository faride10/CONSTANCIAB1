<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'ALUMNOS';
    protected $primaryKey = 'NUM_CONTROL';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'NUM_CONTROL',
        'NOMBRE',
        'CORREO_INSTITUCIONAL',
        'ID_GRUPO',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'ID_GRUPO', 'ID_GRUPO');
    }

    
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'NUM_CONTROL', 'NUM_CONTROL');
    }
}