<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Cambiar * por el dominio del frontend Angular
    'allowed_origins' => [
        'asistencias-itsal.netlify.app',
        'http://localhost:4200', // para pruebas
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Railway + Angular no requieren credenciales
    // pero si usas tokens/cookies cambia a true
    'supports_credentials' => false,

];
