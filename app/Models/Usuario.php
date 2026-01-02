<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; 
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false; 

    protected $fillable = [
        'username',
        'password_hash',
        'nombre',     
        // 'email',   
        'id_rol',
        'id_docente', 
        'needs_password_change', 
    ];

    protected $hidden = [
        'password_hash',
        'remember_token', 
    ];

    protected $casts = [
        'needs_password_change' => 'boolean', 
        'id_rol' => 'integer',
        'id_docente' => 'integer'
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    public function getKey()
    {
        return $this->id_usuario;
    }

    public function getRouteKeyName()
    {
        return 'id_usuario';
    }
}