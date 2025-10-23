<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'DOCENTE';
    protected $primaryKey = 'ID_DOCENTE';
    public $timestamps = true;

    protected $fillable = [
        'NOMBRE',
        'RFC',
        'CORREO',
        'TELEFONO',
    ];

    public function grupo()
    {
        return $this->hasOne(Grupo::class, 'ID_DOCENTE', 'ID_DOCENTE');
    }

    public function usuario() 
    {
        return $this->hasOne(Usuario::class, 'ID_DOCENTE', 'ID_DOCENTE');
    }
}