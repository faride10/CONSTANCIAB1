<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constancia extends Model
{
    use HasFactory;

    protected $table = 'CONSTANCIA'; 
    protected $primaryKey = 'ID_CONSTANCIA'; 
    public $timestamps = false;

    protected $fillable = [
        'NUM_CONTROL', 
        'FECHA_EMISION', 
    ];

    
    public function alumno()
    {
       
        return $this->belongsTo(Alumno::class, 'NUM_CONTROL', 'NUM_CONTROL');
    }
}