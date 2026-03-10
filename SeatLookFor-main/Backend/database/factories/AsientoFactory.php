<?php

namespace Database\Factories;
use App\Models\Asiento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\asiento>
 */
class AsientoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      // Generar todas las combinaciones posibles de fila (A-J) y columna (1-12)
      $filas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
      $columnas = range(1, 12); // Números del 1 al 12

      // Seleccionar fila y columna
      $fila = $this->faker->randomElement($filas);
      $columna = $this->faker->randomElement($columnas);

      return [
       /*    'nombreAsiento' => $fila . $columna, // Ejemplo: A1, B12, etc. */
          'estado' => $this->faker->randomElement(['ocupado', 'libre', 'inactivo']), // Estado aleatorio
          'fila' => $fila,
          'columna' => $columna,
          'idEst' => \App\Models\Establecimiento::factory(), // Relación con la tabla establecimiento
      ];
  }
}
