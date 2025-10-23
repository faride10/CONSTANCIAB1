<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\Usuario;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        
    ];

    public function boot(): void
    {
        Gate::define('isAdmin', function (Usuario $user) {
            return $user->rol->NOMBRE_ROL === 'administrador';
        });
    }
}