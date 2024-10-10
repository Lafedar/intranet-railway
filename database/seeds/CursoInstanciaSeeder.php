<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\CursoInstancia;
use Faker\Factory as Faker;

class CursoInstanciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('es_ES'); // Configura Faker para español
        // Obtener todos los cursos existentes
        $cursos = Curso::all();
        foreach ($cursos as $curso) {
            // Crear 2 instancias por cada curso
            foreach (range(1, 2) as $index) {
                CursoInstancia::create([
                    'id' => $faker->unique()->numberBetween(1, 1000), // Genera un ID único entre 1 y 1000
                    'id_curso' => $curso->id, // Asocia la instancia al curso
                    'fecha_inicio' => $faker->dateTimeBetween('now', '+1 month'), // Fecha de inicio dentro del mes siguiente
                    'fecha_fin' => $faker->dateTimeBetween('+1 month', '+2 months'), // Fecha de fin en el mes posterior
                    'cupo' => $faker->numberBetween(5, 30), // Cupo entre 10 y 30
                    'modalidad' => $faker->randomElement(['Presencial', 'En línea', 'Híbrido']), // Modalidad aleatoria
                    'impartido_por' => $faker->name(), // Nombre del instructor
                    'lugar' => $faker->randomElement(['Sala Vidriada I', 'Auditorio', 'Sala Vidriada II','Gtia de Calidad']), // Dirección aleatoria
                    'estado' => $faker->randomElement(['Activo', 'Inactivo']), // Estado aleatorio
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
