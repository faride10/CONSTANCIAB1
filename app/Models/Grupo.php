<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $table = 'grupo';
    protected $primaryKey = 'id_grupo';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'carrera',
        'id_docente',
    ];

    public function alumnos()
    {
        return $this->hasMany(Alumno::class, 'id_grupo', 'id_grupo');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    public function conferencias()
{
    return $this->belongsToMany(
        Conferencia::class,     
        'conferencia_grupo',    
        'id_grupo',     
        'id_conferencia'    
    );
}
}