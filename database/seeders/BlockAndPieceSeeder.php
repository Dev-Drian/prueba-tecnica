<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Block;
use App\Models\Piece;

class BlockAndPieceSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 5 proyectos
        $projects = Project::factory()->count(5)->create();

        // Para cada proyecto, crear entre 3 y 8 bloques
        foreach ($projects as $project) {
            $blocks = Block::factory()
                ->count(rand(3, 8))
                ->create(['project_id' => $project->id]);

            // Para cada bloque, crear entre 5 y 15 piezas
            foreach ($blocks as $block) {
                Piece::factory()
                    ->count(rand(5, 15))
                    ->create(['block_id' => $block->id]);
            }
        }
    }
} 