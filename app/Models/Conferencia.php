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

    public function grupos()
    {
        return $this->belongsToMany(
            Grupo::class,
            'conferencia_grupo',      
            'ID_CONFERENCIA',       
            'ID_GRUPO'        
        );
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'ID_CONFERENCIA', 'ID_CONFERENCIA');
    }
    public function ponente()
    {
        return $this->belongsTo(Ponente::class, 'ID_PONENTE', 'ID_PONENTE');
    }
}