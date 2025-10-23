<?php

namespace App\Models;   

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conferencia extends Model
{
    use HasFactory;

    protected $table = 'CONFERENCIA';
    protected $primaryKey = 'ID_CONFERENCIA';

    public $timestamps = false;
    protected $fillable = [
        'NOMBRE_CONFERENCIA',
        'TEMA',
        'FECHA_HORA',   
        'LUGAR',
        'NUM_PARTICIPANTES',
        'ID_PONENTE'
    ];

    protected $casts = [
        'FECHA_HORA' => 'datetime', 
        'NUM_PARTICIPANTES' => 'integer',
    ];

    public function ponente()
    {

        return $this->belongsTo(Ponente::class, 'ID_PONENTE', 'ID_PONENTE');
    }
}