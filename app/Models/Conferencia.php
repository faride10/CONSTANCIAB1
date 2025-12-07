<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conferencia extends Model
{
    use HasFactory;

    protected $table = 'conferencia';
    protected $primaryKey = 'id_conferencia';

    public $timestamps = false;
    protected $fillable = [
        'nombre_conferencia',
        'tema',
        'fecha_hora',
        'lugar',
        'num_participantes',
        'id_ponente'
    ];

    public function grupos()
    {
        return $this->belongsToMany(
            Grupo::class,
            'conferencia_grupo',      
            'id_conferencia',       
            'id_grupo'        
        );
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_conferencia', 'id_conferencia');
    }
    public function ponente()
    {
        return $this->belongsTo(Ponente::class, 'id_ponente', 'id_ponente');
    }
}