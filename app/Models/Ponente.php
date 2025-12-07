<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponente extends Model
{
    use HasFactory;

    protected $table = 'ponente';
    protected $primaryKey = 'id_ponente';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'titulo',
        'cargo',
        'empresa',
        'correo',
    ];

    public function conferencias()
    {
    
        return $this->hasMany(Conferencia::class, 'id_ponente', 'id_ponente');
    }
}