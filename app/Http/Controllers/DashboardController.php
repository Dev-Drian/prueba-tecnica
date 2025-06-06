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
        // Obtener total de proyectos
        $totalProjects = Project::count();

        // Obtener total de piezas
        $totalPieces = Piece::count();

        // Obtener piezas pendientes
        $pendingPieces = Piece::with(['block.project'])
            ->where('estado', 'Pendiente')
            ->get()
            ->map(function ($piece) {
                return [
                    'id' => $piece->id,
                    'name' => $piece->nombre,
                    'project' => $piece->block->project->nombre,
                    'priority' => $piece->prioridad,
                    'status' => $piece->estado,
                    'progress' => $piece->progreso,
                    'daysLate' => $piece->dias_retraso
                ];
            });

        // Obtener piezas completadas
        $completedPieces = Piece::where('estado', 'Completado')->count();

        // Obtener datos mensuales (compatible con SQLite)
        $monthlyData = Piece::select(
            DB::raw('strftime("%m", created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('month')
        ->get();

        // Obtener piezas por estado
        $piecesByStatus = Piece::select('estado', DB::raw('COUNT(*) as count'))
            ->groupBy('estado')
            ->get()
            ->map(function ($item) use ($totalPieces) {
                return [
                    'name' => $item->estado,
                    'count' => $item->count,
                    'percentage' => round(($item->count / $totalPieces) * 100)
                ];
            });

        // Obtener actividades recientes
        $recentActivities = Piece::with(['block.project'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($piece) {
                return [
                    'description' => "Pieza {$piece->nombre} actualizada en {$piece->block->project->nombre}",
                    'created_at' => $piece->updated_at
                ];
            });

        // Métricas de rendimiento
        $performanceMetrics = [
            'completionRate' => round(($completedPieces / $totalPieces) * 100),
            'averageProgress' => Piece::avg('progreso'),
            'onTimeDelivery' => Piece::where('dias_retraso', '<=', 0)->count()
        ];

        return Inertia::render('Dashboard', [
            'totalProjects' => $totalProjects,
            'totalPieces' => $totalPieces,
            'pendingPieces' => $pendingPieces,
            'completedPieces' => $completedPieces,
            'monthlyData' => $monthlyData,
            'piecesByStatus' => $piecesByStatus,
            'recentActivities' => $recentActivities,
            'performanceMetrics' => $performanceMetrics
        ]);
    }
} 