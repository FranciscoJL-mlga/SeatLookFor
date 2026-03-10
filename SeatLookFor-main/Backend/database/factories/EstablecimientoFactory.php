<?php

namespace Database\Factories;

use App\Models\Establecimiento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Establecimiento>
 */
class EstablecimientoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ubicacion' => $this->faker->address,
            'nombre' => $this->faker->randomElement(['Teatro los Prados', 'Teatro del Viso', 'Teatro Alan Turing']),
            'imagen' => $this->faker->text(100),
        ];
    }
}
