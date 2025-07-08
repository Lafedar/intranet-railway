<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ruta absoluta en el sistema de archivos
    |--------------------------------------------------------------------------
    | Usado cuando necesitas manipular imágenes con funciones de PHP, mover,
    | copiar o verificar si existen en disco.
    */
    'path' => storage_path(env('IMAGES_DISK_PATH', 'app/public/images')),

    /*
    |--------------------------------------------------------------------------
    | URL pública accesible desde el navegador
    |--------------------------------------------------------------------------
    | Usado en vistas, frontend, o cuando generás enlaces públicos a imágenes.
    */
    'public_url' => env('IMAGES_PUBLIC_URL', '/storage/images'),
];