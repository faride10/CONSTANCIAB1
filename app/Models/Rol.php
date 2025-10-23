<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'ROL';
    protected $primaryKey = 'ID_ROL';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_ROL',
    ];

    
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'ID_ROL', 'ID_ROL');
    }
}