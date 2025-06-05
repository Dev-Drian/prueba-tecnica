<?php

namespace App\Http\Controllers;

use App\Models\PieceRecord;
use App\Models\Piece;
use App\Models\Block;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PieceRecordController extends Controller
{
    public function index()
    {
        try {
            $records = PieceRecord::with(['piece.block.project', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return Inertia::render('PieceRecords/Index', [
                'records' => $records
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los registros: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $projects = Project::select('id', 'nombre', 'codigo_proyecto')->get();
            $blocks = Block::with(['project:id,nombre,codigo_proyecto'])
                ->select('id', 'nombre', 'project_id', 'codigo_bloque')
                ->get();
            $pieces = Piece::where('estado', 'Pendiente')
                ->with(['block.project'])
                ->select('id', 'nombre', 'codigo_pieza', 'block_id', 'peso_teorico')
                ->get();

            return Inertia::render('PieceRecords/Create', [
                'projects' => $projects,
                'blocks' => $blocks,
                'pieces' => $pieces,
                'oldData' => old()
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pieza_id' => 'required|exists:pieces,id',
                'peso_real' => 'required|numeric|min:0',
                'observaciones' => 'nullable|string'
            ]);

            $piece = Piece::findOrFail($validated['pieza_id']);
            $diferencia = $piece->peso_teorico - $validated['peso_real'];

            $record = PieceRecord::create([
                'pieza_id' => $validated['pieza_id'],
                'peso_real' => $validated['peso_real'],
                'diferencia' => $diferencia,
                'fecha_hora' => now(),
                'usuario_id' => auth()->id(),
                'observaciones' => $validated['observaciones'] ?? null
            ]);

            $piece->update(['estado' => 'Fabricada']);

            return redirect()->route('piece-records.index')
                ->with('success', 'Registro creado exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al crear el registro: ' . $e->getMessage());
        }
    }

    public function show(PieceRecord $record)
    {
        try {
            return Inertia::render('PieceRecords/Show', [
                'record' => $record->load(['piece.block.project', 'user'])
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el registro: ' . $e->getMessage());
        }
    }

    public function edit(PieceRecord $record)
    {
        try {
            return Inertia::render('PieceRecords/Edit', [
                'record' => $record->load(['piece.block.project', 'user'])
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el registro: ' . $e->getMessage());
        }
    }

    public function update(Request $request, PieceRecord $record)
    {
        try {
            $validated = $request->validate([
                'peso_real' => 'required|numeric|min:0',
                'observaciones' => 'nullable|string'
            ]);

            $diferencia = $record->piece->peso_teorico - $validated['peso_real'];

            $record->update([
                'peso_real' => $validated['peso_real'],
                'diferencia' => $diferencia,
                'observaciones' => $validated['observaciones'] ?? null
            ]);

            return redirect()->route('piece-records.index')
                ->with('success', 'Registro actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el registro: ' . $e->getMessage());
        }
    }

    public function destroy(PieceRecord $record)
    {
        try {
            $record->delete();
            return redirect()->route('piece-records.index')
                ->with('success', 'Registro eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }
} 