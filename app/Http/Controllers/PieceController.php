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
            $pieces = Piece::with(['block.project'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return Inertia::render('Pieces/Index', [
                'pieces' => $pieces,
                'flash' => session('flash')
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las piezas: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $blocks = Block::with('project')->get();
            return Inertia::render('Pieces/Create', [
                'blocks' => $blocks,
                'old' => old(),
                'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
                'flash' => session('flash')
            ]);
        } catch (\Exception $e) {
            return back()->with('flash', [
                'error' => 'Error al cargar el formulario: ' . $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'codigo_pieza' => 'required|string|max:255|unique:pieces',
                'nombre' => 'required|string|max:255',
                'block_id' => 'required|exists:blocks,id',
                'peso_teorico' => 'required|numeric|min:0',
                'estado' => 'required|in:Pendiente,En_Proceso,Fabricada'
            ], [
                'codigo_pieza.required' => 'El código de la pieza es obligatorio',
                'codigo_pieza.unique' => 'Este código de pieza ya está registrado',
                'nombre.required' => 'El nombre de la pieza es obligatorio',
                'block_id.required' => 'Debe seleccionar un bloque',
                'block_id.exists' => 'El bloque seleccionado no existe',
                'peso_teorico.required' => 'El peso teórico es obligatorio',
                'peso_teorico.numeric' => 'El peso teórico debe ser un número',
                'peso_teorico.min' => 'El peso teórico no puede ser negativo',
                'estado.required' => 'El estado es obligatorio',
                'estado.in' => 'El estado seleccionado no es válido'
            ]);

            $validated['user_id'] = auth()->id();
            $piece = Piece::create($validated);

            return redirect()->route('pieces.index')
                ->with('success', 'Pieza creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la pieza: ' . $e->getMessage());
        }
    }

    public function edit(Piece $piece)
    {
        try {
            $blocks = Block::with('project')->get();
            return Inertia::render('Pieces/Edit', [
                'piece' => $piece->load('block.project'),
                'blocks' => $blocks,
                'old' => old(),
                'flash' => session('flash')
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Piece $piece)
    {
        try {
            $validated = $request->validate([
                'codigo_pieza' => 'required|string|max:255|unique:pieces,codigo_pieza,' . $piece->id,
                'nombre' => 'required|string|max:255',
                'block_id' => 'required|exists:blocks,id',
                'peso_teorico' => 'required|numeric|min:0',
                'estado' => 'required|in:Pendiente,En_Proceso,Fabricada'
            ], [
                'codigo_pieza.required' => 'El código de la pieza es obligatorio',
                'codigo_pieza.unique' => 'Este código de pieza ya está registrado',
                'nombre.required' => 'El nombre de la pieza es obligatorio',
                'block_id.required' => 'Debe seleccionar un bloque',
                'block_id.exists' => 'El bloque seleccionado no existe',
                'peso_teorico.required' => 'El peso teórico es obligatorio',
                'peso_teorico.numeric' => 'El peso teórico debe ser un número',
                'peso_teorico.min' => 'El peso teórico no puede ser negativo',
                'estado.required' => 'El estado es obligatorio',
                'estado.in' => 'El estado seleccionado no es válido'
            ]);

            $piece->update($validated);

            return redirect()->route('pieces.index')
                ->with('success', '¡Pieza actualizada exitosamente!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la pieza: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Piece $piece)
    {
        try {
            $piece->delete();
            return back()->with('success', '¡Pieza eliminada exitosamente!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la pieza: ' . $e->getMessage());
        }
    }
} 