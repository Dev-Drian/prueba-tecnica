<?php

namespace Database\Factories;

use App\Models\Block;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlockFactory extends Factory
{
    protected $model = Block::class;

    public function definition(): array
    {
        $tipos_bloque = [
            'EST' => 'Estructural',
            'MEC' => 'Mecánico',
            'HID' => 'Hidráulico',
            'ELE' => 'Eléctrico',
            'PIP' => 'Piping',
            'INS' => 'Instrumentación',
            'VEN' => 'Ventilación',
            'PRO' => 'Propulsión',
            'NAV' => 'Navegación',
            'COM' => 'Comunicaciones'
        ];

        $tipo = $this->faker->randomElement(array_keys($tipos_bloque));
        $numero = $this->faker->unique()->numberBetween(1, 999);
        
        return [
            'codigo_bloque' => $tipo . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT),
            'nombre' => $tipos_bloque[$tipo] . ' ' . $numero,
            'project_id' => Project::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
} 