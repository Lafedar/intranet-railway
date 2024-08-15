<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\PingCheck;





class HealthCheckServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Health::checks([
            DatabaseCheck::new(), //verifica conexion a la base de datos. 
            SecurityAdvisoriesCheck::new(), //verifica si los paquetes PHP tienen vulnerabilidades de seguridad conocidas.
            CacheCheck::new(), //verifica que la aplicación pueda conectarse a su sistema de caché y leer/escribir en las claves de caché. 
            PingCheck::new()->url('http://intranet.lafedar.net/')->retryTimes(3),  //enviará una solicitud a una URL determinada. Informará de un error cuando esa URL no responda con un código de respuesta exitosa en un segundo.
            DatabaseConnectionCountCheck::new() //cantidad de usuarios conectados a la base de datos
        ->warnWhenMoreConnectionsThan(130)
        ->failWhenMoreConnectionsThan(150)
        ]);
        
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
