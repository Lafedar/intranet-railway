<?php

return [
 
    /*
    |--------------------------------------------------------------------------
    | Imágenes fijas (logos, firmas, íconos) – versionadas y públicas
    |--------------------------------------------------------------------------
    */
    'static' => [
        'path' => public_path('images'),
        'url'  => '/images',  //para las vistas
    ],
 
    /*
    |--------------------------------------------------------------------------
    | Imágenes dinámicas (fotos de usuarios, adjuntos) – no versionadas
    |--------------------------------------------------------------------------
    */
    'dynamic' => [
        'path' => storage_path(env('IMAGES_DISK_PATH', 'app/public/uploads')),
        'url'  => env('IMAGES_PUBLIC_URL', '/storage/uploads'),
    ],
];