<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Piece;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener piezas pendientes agrupadas por proyecto
        $piezasPendientes = Piece::with(['block.project'])
            ->where('estado', 'Pendiente')
            ->get()
            ->groupBy('block.project.nombre');

        // Obtener estadísticas por proyecto
        $estadisticasProyectos = Project::with(['blocks.pieces'])
            ->get()
            ->map(function ($project) {
                $totalPiezas = $project->blocks->flatMap->pieces->count();
                $piezasPendientes = $project->blocks->flatMap->pieces->where('estado', 'Pendiente')->count();
                $piezasFabricadas = $project->blocks->flatMap->pieces->where('estado', 'Fabricada')->count();
                $piezasEnProceso = $project->blocks->flatMap->pieces->where('estado', 'En_Proceso')->count();

                return [
                    'id' => $project->id,
                    'nombre' => $project->nombre,
                    'codigo' => $project->codigo_proyecto,
                    'total_piezas' => $totalPiezas,
                    'piezas_pendientes' => $piezasPendientes,
                    'piezas_fabricadas' => $piezasFabricadas,
                    'piezas_en_proceso' => $piezasEnProceso,
                ];
            });

        return Inertia::render('Dashboard', [
            'piezasPendientes' => $piezasPendientes,
            'estadisticasProyectos' => $estadisticasProyectos
        ]);
    }
} 