<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumno';
    protected $primaryKey = 'num_control';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'num_control',
        'nombre',
        'correo_institucional',
        'id_grupo',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'num_control', 'num_control');
    }
}
