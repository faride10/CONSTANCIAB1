<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;


    protected $table = 'ASISTENCIA';
    protected $primaryKey = 'ID_ASISTENCIA';

    public $timestamps = false;

    protected $fillable = [
        'ID_CONFERENCIA',
        'NUM_CONTROL',
    ];

    public function conferencia()
    {
        return $this->belongsTo(Conferencia::class, 'ID_CONFERENCIA', 'ID_CONFERENCIA');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'NUM_CONTROL', 'NUM_CONTROL');
    }
}