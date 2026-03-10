<?php

namespace Database\Factories;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evento>
 */
class EventoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
{
    return [
        'estado' => $this->faker->randomElement(['activo', 'inactivo', 'pendiente']),
        'valoracion' => $this->faker->randomFloat(1, 1, 5),
        'ubicacion' => $this->faker->address(),
        'portada' => $this->faker->text(100),
        'tipo' => $this->faker->randomElement(['obra de teatro', 'musical', 'concierto']),
        'titulo' => $this->faker->sentence(4),
        'descripcion' => $this->faker->text(100),
        'duracion' => $this->faker->time('H:i:s'),
        'fecha' => $this->faker->date(),
        'categoria' => $this->faker->randomElement(['cultural', 'deportivo', 'educativo', 'musical']),
        'idEst' => 1,
    ];
}

}
