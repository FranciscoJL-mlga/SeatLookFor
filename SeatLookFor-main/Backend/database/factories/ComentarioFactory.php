<?php

namespace Database\Factories;

use App\Models\Comentario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comentario>
 */
class ComentarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'valoracion' => $this->faker->randomFloat(1, 1, 5),
          /*   'comentario_padre_id' => $this->faker->optional()->randomElement(
                Comentario::inRandomOrder()->pluck('id')->toArray()
            ), */
            'opinion' => $this->faker->text(100),
            'foto' => $this->faker->text(100),
            'idUsu'=>\App\Models\Usuario::factory(),
            'idAsi'=>\App\Models\Asiento::factory()
        ];
    }
}





