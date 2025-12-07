<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    protected $table = 'docente';
    protected $primaryKey = 'id_docente';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'rfc',
        'correo',
        'telefono',
    ];

    public function grupo()
    {
        return $this->hasOne(Grupo::class, 'id_docente', 'id_docente');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id_docente', 'id_docente');
    }
}