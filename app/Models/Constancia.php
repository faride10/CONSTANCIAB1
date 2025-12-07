<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constancia extends Model
{
    use HasFactory;

    protected $table = 'constancias'; 
    protected $primaryKey = 'id_constancia'; 
    public $timestamps = false;

    protected $fillable = [
        'num_control', 
        'fecha_emision', 
    ];

    
    public function alumno()
    {
       
        return $this->belongsTo(Alumno::class, 'num_control', 'num_control');
    }
}