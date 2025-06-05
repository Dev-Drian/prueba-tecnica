<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $blocks = Block::with('project')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);
            
            return Inertia::render('Blocks/Index', [
                'blocks' => $blocks
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los bloques: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para crear un bloque
     */
    public function create()
    {
        $projects = Project::all();
        return Inertia::render('Blocks/Create', [
            'projects' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'codigo_bloque' => 'required|string|max:255|unique:blocks,codigo_bloque',
                'nombre' => 'required|string|max:255',
                'project_id' => 'required|exists:projects,id'
            ]);

            Block::create($validated);

            return redirect()->route('blocks.index')
                ->with('success', 'Bloque creado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el bloque: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block)
    {
        try {
            return Inertia::render('Blocks/Show', [
                'block' => $block->load('project')
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el bloque: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Block $block)
    {
        try {
            $projects = Project::all();
            return Inertia::render('Blocks/Edit', [
                'block' => $block,
                'projects' => $projects
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el bloque: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        try {
            $validated = $request->validate([
                'codigo_bloque' => 'required|string|max:255|unique:blocks,codigo_bloque,' . $block->id,
                'nombre' => 'required|string|max:255',
                'project_id' => 'required|exists:projects,id'
            ]);

            $block->update($validated);

            return redirect()->route('blocks.index')
                ->with('success', 'Bloque actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el bloque: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        try {
            $block->delete();
            return redirect()->route('blocks.index')
                ->with('success', 'Bloque eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el bloque: ' . $e->getMessage());
        }
    }
} 