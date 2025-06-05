<?php

namespace Database\Factories;

use App\Models\Piece;
use App\Models\Block;
use Illuminate\Database\Eloquent\Factories\Factory;

class PieceFactory extends Factory
{
    protected $model = Piece::class;

    public function definition(): array
    {
        $estados = ['Pendiente', 'Fabricada', 'En_Proceso'];
        $estado = $this->faker->randomElement($estados);
        
        $fecha_fabricacion = null;
        if ($estado === 'fabricada') {
            $fecha_fabricacion = $this->faker->dateTimeBetween('-1 year', 'now');
        }

        return [
            'codigo_pieza' => 'PZ-' . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'nombre' => $this->faker->words(3, true),
            'estado' => $estado,
            'peso_teorico' => 2,
            'block_id' => Block::factory(),
            'usuario_id' =>1,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
} 