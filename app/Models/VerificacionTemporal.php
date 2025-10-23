<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificacionTemporal extends Model
{
    use HasFactory;

    protected $table = 'VERIFICACION_TEMPORAL'; 

    protected $primaryKey = 'NUM_CONTROL_FK'; 
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'NUM_CONTROL_FK', 
        'CODIGO_OTP', 
        'EXPIRA_EN', 
    ];

   
    public function alumno()
    {

        return $this->belongsTo(Alumno::class, 'NUM_CONTROL_FK', 'NUM_CONTROL');
    }
}