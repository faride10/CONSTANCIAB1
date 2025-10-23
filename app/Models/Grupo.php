<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'GRUPO';
    protected $primaryKey = 'ID_GRUPO';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE',
        'CARRERA',
        'ID_DOCENTE',
    ];

    public function alumnos()
    {
        
        return $this->hasMany(Alumno::class, 'ID_GRUPO', 'ID_GRUPO');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'ID_DOCENTE', 'ID_DOCENTE');
    }
}