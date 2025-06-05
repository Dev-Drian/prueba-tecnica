<?php

namespace App\Http\Controllers;

use App\Models\Piece;
use App\Models\Block;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PieceController extends Controller
{
    public function index()
    {
        try {
            $pieces = Piece::with(['block.project', 'usuario'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return Inertia::render('Pieces/Index', [
                'pieces' => $pieces
            ]);
        } catch (\Exception $e) {
            return back()->with('flash.error', 'Error al cargar las piezas: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $blocks = Block::with('project')->get();
            return Inertia::render('Pieces/Create', [
                'blocks' => $blocks,
                'old' => old()
            ]);
        } catch (\Exception $e) {
            return back()->with('flash.error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Datos recibidos en store:', $request->all());

            $validated = $request->validate([
                'codigo_pieza' => 'required|string|max:255|unique:pieces',
                'nombre' => 'required|string|max:255',
                'block_id' => 'required|exists:blocks,id',
                'peso_teorico' => 'required|numeric|min:0',
                'estado' => 'required|in:Pendiente,Fabricada,En_Proceso'
            ]);

            \Log::info('Datos validados:', $validated);

            // Convertir peso_teorico a decimal
            $validated['peso_teorico'] = (float) $validated['peso_teorico'];

            // Agregar el ID del usuario autenticado
            $validated['usuario_id'] = auth()->id();

            \Log::info('Datos finales para crear pieza:', $validated);

            $piece = Piece::create($validated);

            \Log::info('Pieza creada exitosamente:', $piece->toArray());

            return redirect()->route('pieces.index')
                ->with('flash.success', '¡Pieza creada exitosamente!');
        } catch (\Exception $e) {
            \Log::error('Error al crear pieza: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('flash.error', 'Error al crear la pieza: ' . $e->getMessage());
        }
    }

    public function edit(Piece $piece)
    {
        try {
            $blocks = Block::with('project')->get();
            return Inertia::render('Pieces/Edit', [
                'piece' => $piece->load('block.project'),
                'blocks' => $blocks,
                'old' => old()
            ]);
        } catch (\Exception $e) {
            return back()->with('flash.error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Piece $piece)
    {
        try {
            \Log::info('Datos recibidos para actualizar:', $request->all());

            $validated = $request->validate([
                'codigo_pieza' => 'required|string|max:255|unique:pieces,codigo_pieza,' . $piece->id,
                'nombre' => 'required|string|max:255',
                'block_id' => 'required|exists:blocks,id',
                'peso_teorico' => 'required|numeric|min:0',
                'estado' => 'required|in:Pendiente,Fabricada,En_Proceso'
            ]);

            \Log::info('Datos validados para actualizar:', $validated);

            // Asegurarse de que peso_teorico sea un número decimal
            $validated['peso_teorico'] = floatval($validated['peso_teorico']);

            // Actualizar la pieza
            $updated = $piece->update($validated);

            if (!$updated) {
                throw new \Exception('No se pudo actualizar la pieza');
            }

            \Log::info('Pieza actualizada exitosamente:', $piece->fresh()->toArray());

            return redirect()->route('pieces.index')
                ->with('flash.success', '¡Pieza actualizada exitosamente!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación en actualización:', $e->errors());
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al actualizar la pieza:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->with('flash.error', 'Error al actualizar la pieza: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Piece $piece)
    {
        try {
            $piece->delete();
            return back()->with('flash.success', '¡Pieza eliminada exitosamente!');
        } catch (\Exception $e) {
            return back()->with('flash.error', 'Error al eliminar la pieza: ' . $e->getMessage());
        }
    }
} 