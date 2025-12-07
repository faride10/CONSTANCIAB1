<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificacionTemporal extends Model
{
    use HasFactory;

    protected $table = 'verificacion_temporal'; 

    protected $primaryKey = 'num_control_fk'; 
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'num_control_fk', 
        'codigo_otp', 
        'expira_en', 
    ];

   
    public function alumno()
    {

        return $this->belongsTo(Alumno::class, 'num_control_fk', 'num_control');
    }
}