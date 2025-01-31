<?php

use Illuminate\Database\Seeder;
use Database\Seeders\CursoSeeder;
use Database\Seeders\CursoInstanciaSeeder; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // $this->call(CursoSeeder::class);
         // Llama al seeder de instancias de cursos
         $this->call(CursoInstanciaSeeder::class);
    }
}
