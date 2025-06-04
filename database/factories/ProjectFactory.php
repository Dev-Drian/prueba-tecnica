<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipos = [
            'REP' => 'Reparación',
            'MANT' => 'Mantenimiento',
            'CONS' => 'Construcción',
            'MOD' => 'Modernización'
        ];

        $tipos_buque = [
            'BG' => 'Buque de Guerra',
            'SUB' => 'Submarino',
            'PAT' => 'Patrullera',
            'FRAG' => 'Fragata',
            'LP' => 'Lancha Patrullera',
            'BA' => 'Buque de Apoyo',
            'BI' => 'Buque de Investigación',
            'BT' => 'Buque de Transporte',
            'BE' => 'Buque de Entrenamiento',
            'BR' => 'Buque de Rescate'
        ];

        $tipo = $this->faker->randomElement(array_keys($tipos));
        $tipo_buque = $this->faker->randomElement(array_keys($tipos_buque));
        $numero = $this->faker->unique()->numberBetween(1, 999);
        
        return [
            'codigo_proyecto' => $tipo . '-' . $tipo_buque . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT),
            'nombre' => $tipos[$tipo] . ' de ' . $tipos_buque[$tipo_buque] . ' ' . $numero,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
}
