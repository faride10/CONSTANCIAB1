<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponente extends Model
{
    use HasFactory;

    protected $table = 'PONENTE';
    protected $primaryKey = 'ID_PONENTE';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE',
        'TITULO',
        'CARGO',
        'EMPRESA',
        'CORREO',
    ];

    public function conferencias()
    {
    
        return $this->hasMany(Conferencia::class, 'ID_PONENTE', 'ID_PONENTE');
    }
}