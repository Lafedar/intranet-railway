<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Curso;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curso>
 */
class CursoFactory extends Factory
{
    protected $model = Curso::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Usar la instancia de Faker directamente
        $faker = FakerFactory::create('es_ES');

        return [
            'id' => $faker->unique()->numberBetween(1, 1000), // Genera un ID único entre 1 y 1000
            'titulo' => $faker->sentence(3, true), // Genera un título de 3 palabras en español
            'descripcion' => $faker->paragraph(2, true), // Genera un párrafo de 2 líneas en español
            'obligatorio' => $faker->boolean(), // 0 o 1
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
