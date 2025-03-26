<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Faker\Factory as Faker;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crea una instancia de Faker con el locale español
        $faker = Faker::create('es_ES'); // Configura Faker para español
        $titulos = [
            'Introducción a la programación',
            'Desarrollo web con PHP',
            'Course de administración de bases de datos',
            'Gestión de proyectos ágiles',
            'Marketing digital',
            'Seguridad informática',
            'Análisis de datos',
            'Comunicación efectiva',
            'Diseño gráfico',
            'Recursos humanos',
        ];
        // Crear algunos cursos de ejemplo
        foreach (range(1, 10) as $index) {
            Course::create([
                'id' => $faker->unique()->numberBetween(1, 1000), // Genera un ID único entre 1 y 1000
                'titulo' => $faker->randomElement($titulos), // Título del curso en español
                'descripcion' => $faker->realText(100, 2), // Descripción del curso en español
                'obligatorio' => $faker->boolean() ? 1 : 0, // Aleatorio: 1 (obligatorio) o 0 (no obligatorio)
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
