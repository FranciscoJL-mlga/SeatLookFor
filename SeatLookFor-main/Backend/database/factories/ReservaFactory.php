<?php

namespace Database\Factories;
use App\Models\Reserva;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fechaReserva' => $this->faker->date(),
            'precio' => $this->faker->randomFloat(2, 100, 1000), 
            'descuento' => $this->faker->randomFloat(2, 0, 100), 
            'total' => function (array $attributes) {
                return $attributes['precio'] - $attributes['descuento']; // Total calculado
            },

            'idEve' => \App\Models\Evento::factory(),
            'idAsi' => \App\Models\Asiento::factory(), 
            'idUsu' => \App\Models\Usuario::factory(), 
        ];
    }
}
