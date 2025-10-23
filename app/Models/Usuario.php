<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'USUARIO';
    protected $primaryKey = 'ID_USUARIO';
    public $timestamps = false; 

    protected $fillable = [
        'USERNAME',
        'PASSWORD_HASH',
        'ID_ROL',
        'ID_DOCENTE', 
        'needs_password_change', 
    ];

    protected $hidden = [
        'PASSWORD_HASH',
    ];

    protected $casts = [
        'needs_password_change' => 'boolean', 
  
    ];

    public function getAuthPassword()
    {
        return $this->PASSWORD_HASH;
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'ID_ROL', 'ID_ROL');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'ID_DOCENTE', 'ID_DOCENTE');
    }
}